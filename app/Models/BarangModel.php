<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kode_barang',
        'nama_barang',
        'id_kategori',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok',
        'stok_minimum',
        'keterangan',
        'gambar',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'kode_barang' => 'required|max_length[50]|is_unique[barang.kode_barang,id,{id}]',
        'nama_barang' => 'required|min_length[2]|max_length[150]',
        'id_kategori' => 'permit_empty|integer',
        'satuan'      => 'required|max_length[30]',
        'harga_beli'  => 'required|decimal',
        'harga_jual'  => 'required|decimal',
        'stok'        => 'required|integer',
        'stok_minimum' => 'permit_empty|integer',
    ];
    protected $validationMessages = [
        'kode_barang' => [
            'required'  => 'Kode barang wajib diisi.',
            'is_unique' => 'Kode barang sudah digunakan.',
        ],
        'nama_barang' => [
            'required' => 'Nama barang wajib diisi.',
        ],
        'satuan' => [
            'required' => 'Satuan wajib diisi.',
        ],
        'harga_beli' => [
            'required' => 'Harga beli wajib diisi.',
            'decimal'  => 'Harga beli harus berupa angka.',
        ],
        'harga_jual' => [
            'required' => 'Harga jual wajib diisi.',
            'decimal'  => 'Harga jual harus berupa angka.',
        ],
        'stok' => [
            'required' => 'Stok awal wajib diisi.',
            'integer'  => 'Stok harus berupa angka bulat.',
        ],
    ];
    protected $skipValidation = false;

    // Ambil barang beserta nama kategori
    public function getBarangWithKategori()
    {
        return $this->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->findAll();
    }

    // Ambil barang dengan stok di bawah minimum (untuk alert dashboard)
    public function getStokMinimum()
    {
        return $this->select('barang.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id = barang.id_kategori', 'left')
            ->where('barang.stok <=', $this->db->quoteLiteral('barang.stok_minimum'), false)
            ->where('barang.stok_minimum >', 0)
            ->findAll();
    }

    // Generate kode barang otomatis
    public function generateKode()
    {
        $last = $this->select('kode_barang')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$last) {
            return 'BRG-001';
        }

        $num = (int) substr($last['kode_barang'], 4);
        return 'BRG-' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
