<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\KategoriModel;

class BarangController extends BaseController
{
    protected BarangModel $model;
    protected KategoriModel $kategoriModel;
    protected string $uploadError = '';

    public function __construct()
    {
        $this->model        = new BarangModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $perPage  = 10;
        $search   = $this->request->getGet('search');
        $kategori = $this->request->getGet('kategori');

        $builder = $this->model->select('barang.*, kategori.nama_kategori')
                               ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                               ->orderBy('barang.nama_barang');

        if ($search) {
            $builder->groupStart()
                    ->like('barang.nama_barang', $search)
                    ->orLike('barang.kode_barang', $search)
                    ->groupEnd();
        }
        if ($kategori) {
            $builder->where('barang.id_kategori', $kategori);
        }

        $total   = $builder->countAllResults(false);
        $barangs = $builder->paginate($perPage, 'default');
        $pager   = $this->model->pager;

        return view('barang/index', [
            'title'     => 'Data Barang',
            'barangs'   => $barangs,
            'pager'     => $pager,
            'total'     => $total,
            'search'    => $search,
            'kategori'  => $kategori,
            'kategoris' => $this->kategoriModel->orderBy('nama_kategori')->findAll(),
        ]);
    }

    public function create()
    {
        return view('barang/form', [
            'title'     => 'Tambah Barang',
            'barang'    => null,
            'kategoris' => $this->kategoriModel->orderBy('nama_kategori')->findAll(),
            'kode'      => $this->model->generateKode(),
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();

        $gambar = $this->handleUploadGambar();
        if ($gambar === false) {
            return redirect()->back()->withInput()->with('error', $this->uploadError);
        }
        if ($gambar !== null) {
            $data['gambar'] = $gambar;
        }

        if (!$this->model->save($data)) {
            if (!empty($gambar)) {
                @unlink(FCPATH . 'uploads/barang/' . $gambar);
            }
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('barang')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) return redirect()->to('barang')->with('error', 'Barang tidak ditemukan.');

        return view('barang/form', [
            'title'     => 'Edit Barang',
            'barang'    => $barang,
            'kategoris' => $this->kategoriModel->orderBy('nama_kategori')->findAll(),
            'kode'      => $barang['kode_barang'],
        ]);
    }

    public function update($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) return redirect()->to('barang')->with('error', 'Barang tidak ditemukan.');

        $data = $this->request->getPost();

        // Hapus gambar lama jika user mencentang hapus
        $hapusGambar = $this->request->getPost('hapus_gambar');

        $gambar = $this->handleUploadGambar();
        if ($gambar === false) {
            return redirect()->back()->withInput()->with('error', $this->uploadError);
        }

        if ($gambar !== null) {
            $data['gambar'] = $gambar;
            if (!empty($barang['gambar'])) {
                @unlink(FCPATH . 'uploads/barang/' . $barang['gambar']);
            }
        } elseif ($hapusGambar && !empty($barang['gambar'])) {
            @unlink(FCPATH . 'uploads/barang/' . $barang['gambar']);
            $data['gambar'] = null;
        }

        if (!$this->model->update($id, $data)) {
            if (!empty($gambar)) {
                @unlink(FCPATH . 'uploads/barang/' . $gambar);
            }
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('barang')->with('success', 'Barang berhasil diperbarui.');
    }

    public function delete($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) return redirect()->to('barang')->with('error', 'Barang tidak ditemukan.');

        if (!empty($barang['gambar'])) {
            @unlink(FCPATH . 'uploads/barang/' . $barang['gambar']);
        }

        $this->model->delete($id);
        return redirect()->to('barang')->with('success', 'Barang berhasil dihapus.');
    }

    public function show($id)
    {
        $barang = $this->model->select('barang.*, kategori.nama_kategori')
                              ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
                              ->find($id);
        if (!$barang) return redirect()->to('barang')->with('error', 'Barang tidak ditemukan.');

        return view('barang/show', ['title' => 'Detail Barang', 'barang' => $barang]);
    }

    /**
     * Proses upload gambar barang.
     * Return: string nama file baru jika sukses, null jika tidak ada upload, false jika gagal.
     */
    protected function handleUploadGambar()
    {
        $file = $this->request->getFile('gambar');
        if (!$file || !$file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        $allowedExt  = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $maxSize     = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file->getMimeType(), $allowedMime, true) ||
            !in_array(strtolower($file->getExtension()), $allowedExt, true)) {
            $this->uploadError = 'Format gambar harus JPG, PNG, WEBP, atau GIF.';
            return false;
        }

        if ($file->getSize() > $maxSize) {
            $this->uploadError = 'Ukuran gambar maksimal 2 MB.';
            return false;
        }

        $targetDir = FCPATH . 'uploads/barang';
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }

        $newName = $file->getRandomName();
        $file->move($targetDir, $newName);
        return $newName;
    }

    // API: ambil harga barang untuk AJAX di form penjualan/stok masuk
    public function getHarga($id)
    {
        $barang = $this->model->find($id);
        if (!$barang) return $this->response->setJSON(['error' => 'Barang tidak ditemukan'])->setStatusCode(404);

        return $this->response->setJSON([
            'id'         => $barang['id'],
            'nama'       => $barang['nama_barang'],
            'satuan'     => $barang['satuan'],
            'harga_beli' => $barang['harga_beli'],
            'harga_jual' => $barang['harga_jual'],
            'stok'       => $barang['stok'],
        ]);
    }
}
