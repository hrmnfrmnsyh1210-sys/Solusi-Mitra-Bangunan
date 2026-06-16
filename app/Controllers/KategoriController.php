<?php

namespace App\Controllers;

use App\Models\KategoriModel;

class KategoriController extends BaseController
{
    protected KategoriModel $model;

    public function __construct()
    {
        $this->model = new KategoriModel();
    }

    public function index()
    {
        return view('kategori/index', [
            'title'     => 'Data Kategori',
            'kategoris' => $this->model->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('kategori/form', ['title' => 'Tambah Kategori', 'kategori' => null]);
    }

    public function store()
    {
        if (!$this->model->save($this->request->getPost())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $kategori = $this->model->find($id);
        if (!$kategori) return redirect()->to('kategori')->with('error', 'Kategori tidak ditemukan.');

        return view('kategori/form', ['title' => 'Edit Kategori', 'kategori' => $kategori]);
    }

    public function update($id)
    {
        $kategori = $this->model->find($id);
        if (!$kategori) return redirect()->to('kategori')->with('error', 'Kategori tidak ditemukan.');

        $this->model->skipValidation(false);
        if (!$this->model->update($id, $this->request->getPost())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('kategori')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kategori = $this->model->find($id);
        if (!$kategori) return redirect()->to('kategori')->with('error', 'Kategori tidak ditemukan.');

        $this->model->delete($id);
        return redirect()->to('kategori')->with('success', 'Kategori berhasil dihapus.');
    }
}
