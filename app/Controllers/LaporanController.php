<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\StokMasukModel;
use App\Models\DetailStokMasukModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanController extends BaseController
{
    // ─────────────────────────────────────────────
    // INDEX
    // ─────────────────────────────────────────────
    public function index()
    {
        return view('laporan/index', ['title' => 'Laporan']);
    }

    // ─────────────────────────────────────────────
    // LAPORAN PENJUALAN
    // ─────────────────────────────────────────────
    public function penjualan()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');

        $data = $this->_queryPenjualan($dari, $sampai);

        return view('laporan/penjualan', [
            'title'            => 'Laporan Penjualan',
            'rows'             => $data,
            'dari'             => $dari,
            'sampai'           => $sampai,
            'total'            => array_sum(array_column($data, 'total_harga')),
            'total_keuntungan' => array_sum(array_column($data, 'total_keuntungan')),
        ]);
    }

    public function penjualanPdf()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $rows   = $this->_queryPenjualan($dari, $sampai);
        $total            = array_sum(array_column($rows, 'total_harga'));
        $total_keuntungan = array_sum(array_column($rows, 'total_keuntungan'));

        $html = view('laporan/pdf/penjualan', compact('rows', 'dari', 'sampai', 'total', 'total_keuntungan'));
        $this->_streamPdf($html, 'Laporan_Penjualan_' . $dari . '_' . $sampai, 'landscape');
    }

    public function penjualanExcel()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $rows   = $this->_queryPenjualan($dari, $sampai);
        $total            = array_sum(array_column($rows, 'total_harga'));
        $total_keuntungan = array_sum(array_column($rows, 'total_keuntungan'));

        $ss    = new Spreadsheet();
        $sheet = $ss->getActiveSheet()->setTitle('Penjualan');

        // Header judul
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Periode: ' . $this->_formatTanggal($dari) . ' s/d ' . $this->_formatTanggal($sampai));
        $this->_styleTitle($sheet, 'A1:H1', 'A2:H2');

        // Kolom header
        $headers = ['No', 'No Transaksi', 'Tanggal', 'Nama Pembeli', 'Total Harga', 'Bayar', 'Kembalian', 'Keuntungan'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($cols[$i] . '4', $h);
        }
        $this->_styleHeader($sheet, 'A4:H4');

        // Data
        $row = 5;
        foreach ($rows as $no => $r) {
            $sheet->setCellValue('A' . $row, $no + 1);
            $sheet->setCellValue('B' . $row, $r['no_transaksi']);
            $sheet->setCellValue('C' . $row, date('d/m/Y', strtotime($r['tanggal_jual'])));
            $sheet->setCellValue('D' . $row, $r['nama_pembeli']);
            $sheet->setCellValue('E' . $row, (float) $r['total_harga']);
            $sheet->setCellValue('F' . $row, (float) $r['bayar']);
            $sheet->setCellValue('G' . $row, (float) $r['kembalian']);
            $sheet->setCellValue('H' . $row, (float) $r['total_keuntungan']);
            $row++;
        }

        // Total row
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $total);
        $sheet->setCellValue('H' . $row, $total_keuntungan);
        $this->_styleTotalRow($sheet, 'A' . $row . ':H' . $row);

        // Warnai kolom keuntungan header & total
        $sheet->getStyle('H4')->getFont()->getColor()->setRGB('065F46');
        $sheet->getStyle('H4')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D1FAE5');

        // Format angka
        $numFmt = '#,##0';
        foreach (['E', 'F', 'G', 'H'] as $col) {
            $sheet->getStyle($col . '5:' . $col . $row)->getNumberFormat()->setFormatCode($numFmt);
        }
        $this->_styleDataRows($sheet, 'A5:H' . ($row - 1));
        $this->_autoWidth($sheet, $cols);

        $this->_streamExcel($ss, 'Laporan_Penjualan_' . $dari . '_' . $sampai);
    }

    // ─────────────────────────────────────────────
    // LAPORAN STOK BARANG
    // ─────────────────────────────────────────────
    public function stok()
    {
        $idKategori = $this->request->getGet('id_kategori') ?? '';

        $data = $this->_queryStok($idKategori);

        return view('laporan/stok', [
            'title'       => 'Laporan Stok Barang',
            'rows'        => $data,
            'kategoris'   => (new KategoriModel())->orderBy('nama_kategori')->findAll(),
            'id_kategori' => $idKategori,
        ]);
    }

    public function stokPdf()
    {
        $idKategori = $this->request->getGet('id_kategori') ?? '';
        $rows       = $this->_queryStok($idKategori);
        $kategoriNama = $idKategori ? (new KategoriModel())->find($idKategori)['nama_kategori'] ?? 'Semua' : 'Semua';

        $html = view('laporan/pdf/stok', compact('rows', 'kategoriNama'));
        $this->_streamPdf($html, 'Laporan_Stok_' . date('Ymd'));
    }

    public function stokExcel()
    {
        $idKategori   = $this->request->getGet('id_kategori') ?? '';
        $rows         = $this->_queryStok($idKategori);
        $kategoriNama = $idKategori ? (new KategoriModel())->find($idKategori)['nama_kategori'] ?? 'Semua' : 'Semua';

        $ss    = new Spreadsheet();
        $sheet = $ss->getActiveSheet()->setTitle('Stok Barang');

        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'LAPORAN STOK BARANG');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'Kategori: ' . $kategoriNama . '  |  Per Tanggal: ' . date('d/m/Y'));
        $this->_styleTitle($sheet, 'A1:H1', 'A2:H2');

        $headers = ['No', 'Kode Barang', 'Nama Barang', 'Kategori', 'Satuan', 'Stok', 'Stok Min.', 'Status'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($cols[$i] . '4', $h);
        }
        $this->_styleHeader($sheet, 'A4:H4');

        $row = 5;
        foreach ($rows as $no => $r) {
            $stok   = (int) $r['stok'];
            $minStok = (int) $r['stok_minimum'];
            $status = $stok <= 0 ? 'Habis' : ($stok <= $minStok ? 'Hampir Habis' : 'Aman');

            $sheet->setCellValue('A' . $row, $no + 1);
            $sheet->setCellValue('B' . $row, $r['kode_barang']);
            $sheet->setCellValue('C' . $row, $r['nama_barang']);
            $sheet->setCellValue('D' . $row, $r['nama_kategori']);
            $sheet->setCellValue('E' . $row, $r['satuan']);
            $sheet->setCellValue('F' . $row, $stok);
            $sheet->setCellValue('G' . $row, $minStok);
            $sheet->setCellValue('H' . $row, $status);

            // Warnai baris status kritis
            if ($stok <= 0) {
                $sheet->getStyle('A' . $row . ':H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEE2E2');
            } elseif ($stok <= $minStok) {
                $sheet->getStyle('A' . $row . ':H' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FEF9C3');
            }
            $row++;
        }

        $this->_styleDataRows($sheet, 'A5:H' . ($row - 1));
        $this->_autoWidth($sheet, $cols);

        $this->_streamExcel($ss, 'Laporan_Stok_' . date('Ymd'));
    }

    // ─────────────────────────────────────────────
    // LAPORAN BARANG MASUK
    // ─────────────────────────────────────────────
    public function barangMasuk()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');

        $data = $this->_queryBarangMasuk($dari, $sampai);

        return view('laporan/barang_masuk', [
            'title'  => 'Laporan Barang Masuk',
            'rows'   => $data,
            'dari'   => $dari,
            'sampai' => $sampai,
            'total'  => array_sum(array_column($data, 'total_harga')),
        ]);
    }

    public function barangMasukPdf()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $rows   = $this->_queryBarangMasuk($dari, $sampai);
        $total  = array_sum(array_column($rows, 'total_harga'));

        $html = view('laporan/pdf/barang_masuk', compact('rows', 'dari', 'sampai', 'total'));
        $this->_streamPdf($html, 'Laporan_Barang_Masuk_' . $dari . '_' . $sampai, 'landscape');
    }

    public function barangMasukExcel()
    {
        $dari   = $this->request->getGet('dari')   ?? date('Y-m-01');
        $sampai = $this->request->getGet('sampai') ?? date('Y-m-d');
        $rows   = $this->_queryBarangMasuk($dari, $sampai);
        $total  = array_sum(array_column($rows, 'total_harga'));

        $ss    = new Spreadsheet();
        $sheet = $ss->getActiveSheet()->setTitle('Barang Masuk');

        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN BARANG MASUK');
        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Periode: ' . $this->_formatTanggal($dari) . ' s/d ' . $this->_formatTanggal($sampai));
        $this->_styleTitle($sheet, 'A1:F1', 'A2:F2');

        $headers = ['No', 'No Transaksi', 'Tanggal', 'Supplier', 'Total Harga', 'Keterangan'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F'];
        foreach ($headers as $i => $h) {
            $sheet->setCellValue($cols[$i] . '4', $h);
        }
        $this->_styleHeader($sheet, 'A4:F4');

        $row = 5;
        foreach ($rows as $no => $r) {
            $sheet->setCellValue('A' . $row, $no + 1);
            $sheet->setCellValue('B' . $row, $r['no_transaksi']);
            $sheet->setCellValue('C' . $row, date('d/m/Y', strtotime($r['tanggal_masuk'])));
            $sheet->setCellValue('D' . $row, $r['nama_supplier'] ?? '-');
            $sheet->setCellValue('E' . $row, (float) $r['total_harga']);
            $sheet->setCellValue('F' . $row, $r['keterangan'] ?? '-');
            $row++;
        }

        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->setCellValue('E' . $row, $total);
        $this->_styleTotalRow($sheet, 'A' . $row . ':F' . $row);
        $sheet->getStyle('E5:E' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $this->_styleDataRows($sheet, 'A5:F' . ($row - 1));
        $this->_autoWidth($sheet, $cols);

        $this->_streamExcel($ss, 'Laporan_Barang_Masuk_' . $dari . '_' . $sampai);
    }

    // ─────────────────────────────────────────────
    // PRIVATE: QUERY
    // ─────────────────────────────────────────────
    private function _queryPenjualan(string $dari, string $sampai): array
    {
        return \Config\Database::connect()->query("
            SELECT
                p.*,
                COALESCE(SUM((dp.harga_jual - dp.harga_beli) * dp.jumlah), 0) AS total_keuntungan
            FROM penjualan p
            LEFT JOIN detail_penjualan dp ON dp.id_penjualan = p.id
            WHERE p.tanggal_jual >= ? AND p.tanggal_jual <= ?
            GROUP BY p.id
            ORDER BY p.tanggal_jual ASC
        ", [$dari, $sampai])->getResultArray();
    }

    private function _queryStok(string $idKategori): array
    {
        $model = (new BarangModel())
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->orderBy('barang.nama_barang', 'ASC');

        if ($idKategori !== '') {
            $model->where('barang.id_kategori', $idKategori);
        }

        return $model->findAll();
    }

    private function _queryBarangMasuk(string $dari, string $sampai): array
    {
        return (new StokMasukModel())
            ->select('stok_masuk.*, supplier.nama_supplier')
            ->join('supplier', 'supplier.id = stok_masuk.id_supplier', 'left')
            ->where('tanggal_masuk >=', $dari)
            ->where('tanggal_masuk <=', $sampai)
            ->orderBy('tanggal_masuk', 'ASC')
            ->findAll();
    }

    // ─────────────────────────────────────────────
    // PRIVATE: OUTPUT
    // ─────────────────────────────────────────────
    private function _streamPdf(string $html, string $filename, string $orientation = 'portrait'): void
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation);
        $dompdf->render();
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit;
    }

    private function _streamExcel(Spreadsheet $ss, string $filename): void
    {
        $writer = new Xlsx($ss);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // ─────────────────────────────────────────────
    // PRIVATE: SPREADSHEET STYLING
    // ─────────────────────────────────────────────
    private function _styleTitle($sheet, string $rangeTitle, string $rangeSub): void
    {
        $sheet->getStyle($rangeTitle)->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E1B4B']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle($rangeSub)->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['rgb' => '475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(6);
    }

    private function _styleHeader($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F46E5']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
        ]);
        $sheet->getRowDimension(4)->setRowHeight(18);
    }

    private function _styleDataRows($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E2E8F0']]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'font'      => ['size' => 10],
        ]);
    }

    private function _styleTotalRow($sheet, string $range): void
    {
        $sheet->getStyle($range)->applyFromArray([
            'font'      => ['bold' => true, 'size' => 10],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'C7D2FE']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
    }

    private function _autoWidth($sheet, array $cols): void
    {
        foreach ($cols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    private function _formatTanggal(string $date): string
    {
        $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        [$y, $m, $d] = explode('-', $date);
        return (int)$d . ' ' . $bulan[(int)$m] . ' ' . $y;
    }
}
