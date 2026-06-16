<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyesuaianStokModel extends Model
{
    protected $table            = 'penyesuaian_stok';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_penyesuaian', 'id_barang', 'stok_sebelum', 'stok_sesudah',
        'selisih', 'alasan', 'tanggal', 'keterangan',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'id_barang'   => 'required|integer',
        'stok_sesudah'=> 'required|integer|greater_than_equal_to[0]',
        'alasan'      => 'required|min_length[5]',
        'tanggal'     => 'required|valid_date',
    ];

    public function getAllWithBarang()
    {
        return $this->select('penyesuaian_stok.*, barang.nama_barang, barang.satuan, barang.kode_barang')
                    ->join('barang', 'barang.id = penyesuaian_stok.id_barang')
                    ->orderBy('penyesuaian_stok.tanggal', 'DESC')
                    ->findAll();
    }

    public function generateNoPenyesuaian()
    {
        $prefix = 'PS-' . date('Ymd') . '-';
        $last   = $this->like('no_penyesuaian', $prefix, 'after')
                       ->orderBy('id', 'DESC')
                       ->first();
        if (!$last) return $prefix . '001';
        $num = (int) substr($last['no_penyesuaian'], -3);
        return $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
