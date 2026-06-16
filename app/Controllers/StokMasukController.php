<?php

namespace App\Controllers;

use App\Models\StokMasukModel;
use App\Models\DetailStokMasukModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;

class StokMasukController extends BaseController
{
    protected StokMasukModel $model;
    protected DetailStokMasukModel $detailModel;

    public function __construct()
    {
        $this->model       = new StokMasukModel();
        $this->detailModel = new DetailStokMasukModel();
    }

    public function index()
    {
        $perPage = 10;
        $search  = $this->request->getGet('search');
        $dari    = $this->request->getGet('dari');
        $sampai  = $this->request->getGet('sampai');

        $builder = $this->model->select('stok_masuk.*, supplier.nama_supplier')
                               ->join('supplier', 'supplier.id = stok_masuk.id_supplier', 'left')
                               ->orderBy('stok_masuk.tanggal_masuk', 'DESC');

        if ($search) {
            $builder->groupStart()
                    ->like('stok_masuk.no_transaksi', $search)
                    ->orLike('supplier.nama_supplier', $search)
                    ->groupEnd();
        }
        if ($dari)   $builder->where('stok_masuk.tanggal_masuk >=', $dari);
        if ($sampai) $builder->where('stok_masuk.tanggal_masuk <=', $sampai);

        $total       = $builder->countAllResults(false);
        $stok_masuks = $builder->paginate($perPage, 'default');
        $pager       = $this->model->pager;

        return view('stok_masuk/index', [
            'title'       => 'Stok Masuk',
            'stok_masuks' => $stok_masuks,
            'pager'       => $pager,
            'total'       => $total,
            'search'      => $search,
            'dari'        => $dari,
            'sampai'      => $sampai,
        ]);
    }

    public function create()
    {
        return view('stok_masuk/form', [
            'title'       => 'Tambah Stok Masuk',
            'suppliers'   => (new SupplierModel())->orderBy('nama_supplier')->findAll(),
            'barangs'     => (new BarangModel())->orderBy('nama_barang')->findAll(),
            'no_transaksi' => $this->model->generateNoTransaksi(),
        ]);
    }

    public function store()
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $detail = $this->request->getPost('detail') ?? [];
        if (empty($detail)) {
            return redirect()->back()->withInput()->with('error', 'Tambahkan minimal 1 barang.');
        }

        $totalHarga = array_sum(array_map(fn($d) => $d['jumlah'] * $d['harga_beli'], $detail));

        $header = [
            'no_transaksi'  => $this->request->getPost('no_transaksi'),
            'id_supplier'   => $this->request->getPost('id_supplier') ?: null,
            'tanggal_masuk' => $this->request->getPost('tanggal_masuk'),
            'total_harga'   => $totalHarga,
            'keterangan'    => $this->request->getPost('keterangan'),
        ];

        $this->model->skipValidation(true)->insert($header);
        $id = $this->model->getInsertID();

        $this->detailModel->simpanDanUpdateStok($detail, $id);

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data stok masuk.');
        }

        return redirect()->to('stok-masuk')->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function show($id)
    {
        $stokMasuk = $this->model->getWithSupplier($id);
        if (!$stokMasuk) return redirect()->to('stok-masuk')->with('error', 'Data tidak ditemukan.');

        return view('stok_masuk/show', [
            'title'      => 'Detail Stok Masuk',
            'stok_masuk' => $stokMasuk,
            'details'    => $this->detailModel->getByStokMasuk($id),
        ]);
    }

    public function delete($id)
    {
        $stokMasuk = $this->model->find($id);
        if (!$stokMasuk) return redirect()->to('stok-masuk')->with('error', 'Data tidak ditemukan.');

        // Kurangi stok kembali via query builder langsung
        $details = $this->detailModel->where('id_stok_masuk', $id)->findAll();
        $db      = \Config\Database::connect();
        foreach ($details as $d) {
            $db->table('barang')
               ->where('id', (int) $d['id_barang'])
               ->set('stok', 'stok - ' . (int)$d['jumlah'], false)
               ->update();
        }

        $this->detailModel->where('id_stok_masuk', $id)->delete();
        $this->model->delete($id);

        return redirect()->to('stok-masuk')->with('success', 'Data stok masuk berhasil dihapus.');
    }
}
