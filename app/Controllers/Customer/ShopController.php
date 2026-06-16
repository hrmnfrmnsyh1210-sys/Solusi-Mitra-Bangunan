<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\KategoriModel;

class ShopController extends BaseController
{
    public function index()
    {
        $search     = $this->request->getGet('search');
        $kategoriId = $this->request->getGet('kategori');

        $builder = (new BarangModel())
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->where('barang.stok >', 0)
            ->orderBy('barang.nama_barang', 'ASC');

        if ($search) {
            $builder->groupStart()
                ->like('barang.nama_barang', $search)
                ->orLike('kategori.nama_kategori', $search)
                ->groupEnd();
        }

        if ($kategoriId) {
            $builder->where('barang.id_kategori', (int) $kategoriId);
        }

        return view('customer/shop/index', [
            'title'      => 'Katalog Produk',
            'barangs'    => $builder->findAll(),
            'kategoris'  => (new KategoriModel())->orderBy('nama_kategori')->findAll(),
            'search'     => $search,
            'kategoriId' => $kategoriId,
        ]);
    }

    public function detail($id)
    {
        $barang = (new BarangModel())
            ->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->where('barang.id', (int) $id)
            ->first();

        if (!$barang) {
            return redirect()->to(base_url('shop'))->with('error', 'Produk tidak ditemukan.');
        }

        return view('customer/shop/detail', [
            'title'  => $barang['nama_barang'],
            'barang' => $barang,
        ]);
    }
}
