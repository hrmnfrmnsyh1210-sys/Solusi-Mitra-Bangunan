<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\SupplierModel;
use App\Models\DetailPenjualanModel;
use App\Models\KategoriModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $barangModel          = new BarangModel();
        $penjualanModel       = new PenjualanModel();
        $supplierModel        = new SupplierModel();
        $detailPenjualanModel = new DetailPenjualanModel();

        $rekapHarian = $penjualanModel->rekapHarian();

        $stokMinimumCount = $barangModel->where('stok_minimum >', 0)
                                        ->where('stok <= stok_minimum', null, false)
                                        ->countAllResults();

        $stokMenipis = (new BarangModel())
                        ->select('barang.*, kategori.nama_kategori')
                        ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                        ->where('barang.stok_minimum >', 0)
                        ->where('barang.stok <= barang.stok_minimum', null, false)
                        ->limit(5)->findAll();

        $data = [
            'title'                => 'Dashboard',
            'total_barang'         => $barangModel->countAll(),
            'total_supplier'       => $supplierModel->countAll(),
            'penjualan_hari_ini'   => $rekapHarian['total'] ?? 0,
            'transaksi_hari_ini'   => $rekapHarian['jumlah_transaksi'] ?? 0,
            'keuntungan_hari_ini'  => $rekapHarian['total_keuntungan'] ?? 0,
            'stok_minimum'         => $stokMinimumCount,
            'barang_terlaris'      => $detailPenjualanModel->getBarangTerlaris(5),
            'transaksi_terbaru'    => $penjualanModel->orderBy('created_at', 'DESC')->limit(5)->findAll(),
            'stok_menipis'         => $stokMenipis,
        ];

        return view('dashboard/index', $data);
    }

    // API endpoint: data untuk Chart.js di dashboard
    public function chartData()
    {
        $db   = \Config\Database::connect();
        $mode = $this->request->getGet('mode') ?? '7d';

        // --- Grafik 1: Penjualan & Keuntungan harian (7 atau 30 hari terakhir) ---
        $days = ($mode === '30d') ? 30 : 7;

        $salesRows = $db->query("
            SELECT
                DATE(p.tanggal_jual) as tgl,
                COALESCE(SUM(p.total_harga), 0) as total,
                COALESCE(SUM((dp.harga_jual - dp.harga_beli) * dp.jumlah), 0) as keuntungan
            FROM penjualan p
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id
            WHERE p.tanggal_jual >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
            GROUP BY DATE(p.tanggal_jual)
            ORDER BY tgl ASC
        ", [$days - 1])->getResultArray();

        // Isi semua hari (termasuk yang tidak ada penjualan)
        $salesMap = [];
        foreach ($salesRows as $row) {
            $salesMap[$row['tgl']] = $row;
        }

        $labels = $totals = $profits = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $labels[]  = date('d M', strtotime($date));
            $totals[]  = (float) ($salesMap[$date]['total'] ?? 0);
            $profits[] = (float) ($salesMap[$date]['keuntungan'] ?? 0);
        }

        // --- Grafik 2: Penjualan per Kategori (6 bulan terakhir) ---
        $kategoriRows = $db->query("
            SELECT k.nama_kategori, COALESCE(SUM(dp.subtotal), 0) as total
            FROM kategori k
            LEFT JOIN barang b ON b.id_kategori = k.id
            LEFT JOIN detail_penjualan dp ON dp.id_barang = b.id
            LEFT JOIN penjualan p ON p.id = dp.id_penjualan
                AND p.tanggal_jual >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY k.id, k.nama_kategori
            ORDER BY total DESC
            LIMIT 6
        ")->getResultArray();

        $katLabels = array_column($kategoriRows, 'nama_kategori');
        $katTotals = array_map(fn($r) => (float)$r['total'], $kategoriRows);

        // --- Grafik 3: Tren bulanan (12 bulan terakhir) ---
        $monthlyRows = $db->query("
            SELECT
                DATE_FORMAT(p.tanggal_jual, '%Y-%m') as bln,
                DATE_FORMAT(MIN(p.tanggal_jual), '%b %Y') as label,
                COALESCE(SUM(p.total_harga), 0) as total,
                COALESCE(SUM((dp.harga_jual - dp.harga_beli) * dp.jumlah), 0) as keuntungan
            FROM penjualan p
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id
            WHERE p.tanggal_jual >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(p.tanggal_jual, '%Y-%m')
            ORDER BY bln ASC
        ")->getResultArray();

        return $this->response->setJSON([
            'harian' => [
                'labels'   => $labels,
                'totals'   => $totals,
                'profits'  => $profits,
            ],
            'kategori' => [
                'labels' => $katLabels,
                'totals' => $katTotals,
            ],
            'bulanan' => [
                'labels'   => array_column($monthlyRows, 'label'),
                'totals'   => array_map(fn($r) => (float)$r['total'], $monthlyRows),
                'profits'  => array_map(fn($r) => (float)$r['keuntungan'], $monthlyRows),
            ],
        ]);
    }
}
