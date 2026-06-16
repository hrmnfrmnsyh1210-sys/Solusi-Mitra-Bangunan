<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailStokMasukModel extends Model
{
    protected $table            = 'detail_stok_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_stok_masuk', 'id_barang', 'jumlah', 'harga_beli', 'subtotal',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_stok_masuk' => 'required|integer',
        'id_barang'     => 'required|integer',
        'jumlah'        => 'required|integer|greater_than[0]',
        'harga_beli'    => 'required|decimal',
        'subtotal'      => 'required|decimal',
    ];
    protected $skipValidation = false;

    // Ambil detail stok masuk beserta nama barang
    public function getByStokMasuk($id_stok_masuk)
    {
        return $this->select('detail_stok_masuk.*, barang.nama_barang, barang.satuan, barang.kode_barang')
                    ->join('barang', 'barang.id = detail_stok_masuk.id_barang')
                    ->where('detail_stok_masuk.id_stok_masuk', $id_stok_masuk)
                    ->findAll();
    }

    // Simpan detail & update stok barang secara otomatis
    public function simpanDanUpdateStok(array $detail, int $id_stok_masuk)
    {
        $db = \Config\Database::connect();

        foreach ($detail as $item) {
            $item['id_stok_masuk'] = $id_stok_masuk;
            $item['subtotal']      = (int)$item['jumlah'] * (float)$item['harga_beli'];
            $this->insert($item);

            // Tambah stok via query builder langsung — menghindari validasi model
            $db->table('barang')
               ->where('id', (int) $item['id_barang'])
               ->set('stok', 'stok + ' . (int)$item['jumlah'], false)
               ->update();
        }
    }
}
