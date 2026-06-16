<?php

namespace App\Controllers;

use App\Models\SupplierModel;

class SupplierController extends BaseController
{
    protected SupplierModel $model;

    public function __construct()
    {
        $this->model = new SupplierModel();
    }

    public function index()
    {
        return view('supplier/index', [
            'title'     => 'Data Supplier',
            'suppliers' => $this->model->orderBy('nama_supplier', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('supplier/form', ['title' => 'Tambah Supplier', 'supplier' => null]);
    }

    public function store()
    {
        if (!$this->model->save($this->request->getPost())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('supplier')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $supplier = $this->model->find($id);
        if (!$supplier) return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan.');

        return view('supplier/form', ['title' => 'Edit Supplier', 'supplier' => $supplier]);
    }

    public function update($id)
    {
        $supplier = $this->model->find($id);
        if (!$supplier) return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan.');

        if (!$this->model->update($id, $this->request->getPost())) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->model->errors()));
        }
        return redirect()->to('supplier')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function delete($id)
    {
        $supplier = $this->model->find($id);
        if (!$supplier) return redirect()->to('supplier')->with('error', 'Supplier tidak ditemukan.');

        $this->model->delete($id);
        return redirect()->to('supplier')->with('success', 'Supplier berhasil dihapus.');
    }
}
