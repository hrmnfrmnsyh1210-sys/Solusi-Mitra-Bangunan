<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table            = 'penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'no_transaksi', 'tanggal_jual', 'nama_pembeli', 'id_customer',
        'total_harga', 'bayar', 'kembalian', 'metode_bayar', 'keterangan',
        'status_bayar', 'bukti_bayar', 'tanggal_bayar',
    ];

    /**
     * Label & warna badge untuk status pembayaran (dipakai di view).
     */
    public static function statusBadge(?string $status): array
    {
        return match ($status) {
            'lunas'   => ['label' => 'Lunas',                 'class' => 'bg-emerald-100 text-emerald-700'],
            'batal'   => ['label' => 'Dibatalkan',            'class' => 'bg-rose-100 text-rose-600'],
            default   => ['label' => 'Menunggu Pembayaran',   'class' => 'bg-amber-100 text-amber-700'],
        };
    }

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'tanggal_jual' => 'required|valid_date',
        'total_harga'  => 'permit_empty|decimal',
        'bayar'        => 'permit_empty|decimal',
        'kembalian'    => 'permit_empty|decimal',
    ];
    protected $validationMessages = [
        'tanggal_jual' => [
            'required'   => 'Tanggal penjualan wajib diisi.',
            'valid_date' => 'Format tanggal tidak valid.',
        ],
    ];
    protected $skipValidation = false;

    // Ambil semua penjualan dengan total item dan total keuntungan
    public function getAllWithCount()
    {
        return $this->select('
                    penjualan.*,
                    COUNT(detail_penjualan.id) as jumlah_item,
                    COALESCE(SUM((detail_penjualan.harga_jual - detail_penjualan.harga_beli) * detail_penjualan.jumlah), 0) as total_keuntungan
                ')
                    ->join('detail_penjualan', 'detail_penjualan.id_penjualan = penjualan.id', 'left')
                    ->groupBy('penjualan.id')
                    ->orderBy('penjualan.tanggal_jual', 'DESC')
                    ->findAll();
    }

    // Rekap penjualan per hari (untuk dashboard) — termasuk keuntungan
    public function rekapHarian($tanggal = null)
    {
        $tanggal = $tanggal ?? date('Y-m-d');

        return $this->db->query("
            SELECT
                COALESCE(SUM(p.total_harga), 0)                                                           AS total,
                COUNT(DISTINCT p.id)                                                                       AS jumlah_transaksi,
                COALESCE(SUM((dp.harga_jual - dp.harga_beli) * dp.jumlah), 0)                            AS total_keuntungan
            FROM penjualan p
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id
            WHERE p.tanggal_jual = ? AND p.status_bayar = 'lunas'
        ", [$tanggal])->getRowArray();
    }

    // Rekap penjualan per bulan
    public function rekapBulanan($bulan = null, $tahun = null)
    {
        $bulan = $bulan ?? date('m');
        $tahun = $tahun ?? date('Y');

        return $this->select('DATE(tanggal_jual) as tanggal, SUM(total_harga) as total, COUNT(id) as jumlah_transaksi')
                    ->where('MONTH(tanggal_jual)', $bulan)
                    ->where('YEAR(tanggal_jual)', $tahun)
                    ->where('status_bayar', 'lunas')
                    ->groupBy('DATE(tanggal_jual)')
                    ->orderBy('tanggal_jual', 'ASC')
                    ->findAll();
    }

    // Generate nomor transaksi otomatis: PJ-YYYYMMDD-XXX
    public function generateNoTransaksi()
    {
        $prefix = 'PJ-' . date('Ymd') . '-';
        $last   = $this->like('no_transaksi', $prefix, 'after')
                       ->orderBy('id', 'DESC')
                       ->first();

        if (!$last) {
            return $prefix . '001';
        }

        $num = (int) substr($last['no_transaksi'], -3);
        return $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
