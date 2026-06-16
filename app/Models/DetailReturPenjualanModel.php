<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailReturPenjualanModel extends Model
{
    protected $table            = 'detail_retur_penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'id_retur', 'id_barang', 'jumlah', 'harga_jual', 'subtotal',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getByRetur($id_retur)
    {
        return $this->select('detail_retur_penjualan.*, barang.nama_barang, barang.satuan, barang.kode_barang')
                    ->join('barang', 'barang.id = detail_retur_penjualan.id_barang')
                    ->where('detail_retur_penjualan.id_retur', $id_retur)
                    ->findAll();
    }

    public function simpanDanKembalikanStok(array $detail, int $id_retur)
    {
        $db = \Config\Database::connect();

        foreach ($detail as $item) {
            $item['id_retur']  = $id_retur;
            $item['subtotal']  = (int)$item['jumlah'] * (float)$item['harga_jual'];
            $this->insert($item);

            // Kembalikan stok ke gudang
            $db->table('barang')
               ->where('id', (int) $item['id_barang'])
               ->set('stok', 'stok + ' . (int)$item['jumlah'], false)
               ->update();
        }
    }
}
