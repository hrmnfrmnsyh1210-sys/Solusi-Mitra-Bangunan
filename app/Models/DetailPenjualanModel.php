<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table            = 'detail_penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_penjualan', 'id_barang', 'jumlah', 'harga_beli', 'harga_jual', 'subtotal',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_penjualan' => 'required|integer',
        'id_barang'    => 'required|integer',
        'jumlah'       => 'required|integer|greater_than[0]',
        'harga_jual'   => 'required|decimal',
        'subtotal'     => 'required|decimal',
    ];
    protected $skipValidation = false;

    // Ambil detail penjualan beserta nama barang
    public function getByPenjualan($id_penjualan)
    {
        return $this->select('detail_penjualan.*, barang.nama_barang, barang.satuan, barang.kode_barang')
                    ->join('barang', 'barang.id = detail_penjualan.id_barang')
                    ->where('detail_penjualan.id_penjualan', $id_penjualan)
                    ->findAll();
    }

    // Simpan detail & kurangi stok barang secara otomatis
    // Snapshot harga_beli dari barang saat ini agar keuntungan bisa dihitung akurat
    public function simpanDanKurangiStok(array $detail, int $id_penjualan)
    {
        $barangModel = new BarangModel();
        $db          = \Config\Database::connect();

        foreach ($detail as $item) {
            $barang = $barangModel->find($item['id_barang']);

            $item['id_penjualan'] = $id_penjualan;
            $item['subtotal']     = (int)$item['jumlah'] * (float)$item['harga_jual'];
            $item['harga_beli']   = $barang ? (float) $barang['harga_beli'] : 0;

            $this->insert($item);

            // Kurangi stok via query builder langsung — menghindari validasi model
            // yang mewajibkan semua field hadir saat update
            $db->table('barang')
               ->where('id', (int) $item['id_barang'])
               ->set('stok', 'stok - ' . (int)$item['jumlah'], false)
               ->update();
        }
    }

    // Barang terlaris (untuk laporan & dashboard)
    public function getBarangTerlaris($limit = 5)
    {
        return $this->select('
                    detail_penjualan.id_barang,
                    barang.nama_barang,
                    barang.satuan,
                    SUM(detail_penjualan.jumlah) as total_terjual,
                    SUM(detail_penjualan.subtotal) as total_pendapatan,
                    SUM((detail_penjualan.harga_jual - detail_penjualan.harga_beli) * detail_penjualan.jumlah) as total_keuntungan
                ')
                    ->join('barang', 'barang.id = detail_penjualan.id_barang')
                    ->groupBy('detail_penjualan.id_barang')
                    ->orderBy('total_terjual', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
