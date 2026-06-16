<?php

namespace App\Controllers;

use App\Models\ReturPenjualanModel;
use App\Models\DetailReturPenjualanModel;
use App\Models\PenyesuaianStokModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\BarangModel;

class ReturController extends BaseController
{
    protected ReturPenjualanModel $model;
    protected DetailReturPenjualanModel $detailModel;
    protected PenyesuaianStokModel $penyesuaianModel;

    public function __construct()
    {
        $this->model            = new ReturPenjualanModel();
        $this->detailModel      = new DetailReturPenjualanModel();
        $this->penyesuaianModel = new PenyesuaianStokModel();
    }

    // =====================================================================
    // RETUR PENJUALAN
    // =====================================================================

    public function index()
    {
        return view('retur/index', [
            'title'  => 'Retur Penjualan',
            'returs' => $this->model->getAllWithDetail(),
        ]);
    }

    public function create()
    {
        return view('retur/form', [
            'title'        => 'Tambah Retur Penjualan',
            'penjualans'   => (new PenjualanModel())->orderBy('tanggal_jual', 'DESC')->findAll(),
            'no_retur'     => $this->model->generateNoRetur(),
        ]);
    }

    // AJAX: ambil detail item dari transaksi penjualan tertentu
    public function getDetailPenjualan($id)
    {
        $details = (new DetailPenjualanModel())->getByPenjualan($id);
        return $this->response->setJSON($details);
    }

    public function store()
    {
        $detail = $this->request->getPost('detail') ?? [];
        if (empty($detail)) {
            return redirect()->back()->withInput()->with('error', 'Tambahkan minimal 1 barang yang diretur.');
        }

        // Filter detail dengan jumlah > 0
        $detail = array_filter($detail, fn($d) => isset($d['jumlah']) && (int)$d['jumlah'] > 0);
        if (empty($detail)) {
            return redirect()->back()->withInput()->with('error', 'Jumlah retur harus lebih dari 0.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $header = [
            'no_retur'      => $this->request->getPost('no_retur'),
            'id_penjualan'  => $this->request->getPost('id_penjualan') ?: null,
            'tanggal_retur' => $this->request->getPost('tanggal_retur'),
            'alasan'        => $this->request->getPost('alasan'),
            'keterangan'    => $this->request->getPost('keterangan'),
        ];

        $this->model->skipValidation(true)->insert($header);
        $id = $this->model->getInsertID();

        $this->detailModel->simpanDanKembalikanStok(array_values($detail), $id);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan retur.');
        }

        return redirect()->to('retur')->with('success', 'Retur berhasil dicatat. Stok barang telah dikembalikan.');
    }

    public function show($id)
    {
        $retur = $this->model->select('retur_penjualan.*, penjualan.no_transaksi as no_transaksi_asal')
                             ->join('penjualan', 'penjualan.id = retur_penjualan.id_penjualan', 'left')
                             ->find($id);
        if (!$retur) return redirect()->to('retur')->with('error', 'Data tidak ditemukan.');

        return view('retur/show', [
            'title'   => 'Detail Retur',
            'retur'   => $retur,
            'details' => $this->detailModel->getByRetur($id),
        ]);
    }

    public function delete($id)
    {
        $retur = $this->model->find($id);
        if (!$retur) return redirect()->to('retur')->with('error', 'Data tidak ditemukan.');

        // Kembalikan stok (kurangi kembali karena retur dibatalkan)
        $details = $this->detailModel->where('id_retur', $id)->findAll();
        $db      = \Config\Database::connect();
        foreach ($details as $d) {
            $db->table('barang')
               ->where('id', (int) $d['id_barang'])
               ->set('stok', 'stok - ' . (int)$d['jumlah'], false)
               ->update();
        }

        $this->detailModel->where('id_retur', $id)->delete();
        $this->model->delete($id);

        return redirect()->to('retur')->with('success', 'Retur berhasil dihapus.');
    }

    // =====================================================================
    // PENYESUAIAN STOK (Stock Opname)
    // =====================================================================

    public function penyesuaian()
    {
        return view('retur/penyesuaian_index', [
            'title'        => 'Penyesuaian Stok',
            'penyesuaians' => $this->penyesuaianModel->getAllWithBarang(),
        ]);
    }

    public function penyesuaianCreate()
    {
        return view('retur/penyesuaian_form', [
            'title'          => 'Penyesuaian Stok',
            'barangs'        => (new BarangModel())->select('barang.*, kategori.nama_kategori')
                                                   ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                                                   ->orderBy('nama_barang')->findAll(),
            'no_penyesuaian' => $this->penyesuaianModel->generateNoPenyesuaian(),
        ]);
    }

    public function penyesuaianStore()
    {
        $idBarang    = (int) $this->request->getPost('id_barang');
        $stokSesudah = (int) $this->request->getPost('stok_sesudah');
        $alasan      = $this->request->getPost('alasan');
        $tanggal     = $this->request->getPost('tanggal');

        $barangModel = new BarangModel();
        $barang      = $barangModel->find($idBarang);
        if (!$barang) {
            return redirect()->back()->withInput()->with('error', 'Barang tidak ditemukan.');
        }

        $stokSebelum = (int) $barang['stok'];
        $selisih     = $stokSesudah - $stokSebelum;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->penyesuaianModel->skipValidation(true)->insert([
            'no_penyesuaian' => $this->request->getPost('no_penyesuaian'),
            'id_barang'      => $idBarang,
            'stok_sebelum'   => $stokSebelum,
            'stok_sesudah'   => $stokSesudah,
            'selisih'        => $selisih,
            'alasan'         => $alasan,
            'tanggal'        => $tanggal,
            'keterangan'     => $this->request->getPost('keterangan'),
        ]);

        // Update stok barang langsung
        $db->table('barang')->where('id', $idBarang)->update(['stok' => $stokSesudah]);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan penyesuaian.');
        }

        $keterangan = $selisih > 0
            ? "Stok bertambah {$selisih} {$barang['satuan']}"
            : ($selisih < 0 ? "Stok berkurang " . abs($selisih) . " {$barang['satuan']}" : "Tidak ada perubahan stok");

        return redirect()->to('retur/penyesuaian')->with('success', "Penyesuaian berhasil. {$keterangan}.");
    }
}
