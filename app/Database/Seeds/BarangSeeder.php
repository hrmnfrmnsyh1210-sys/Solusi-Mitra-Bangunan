<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_barang'   => 'BRG-001',
                'nama_barang'   => 'Semen Portland',
                'id_kategori'   => 1,
                'satuan'        => 'sak',
                'harga_beli'    => 55000,
                'harga_jual'    => 65000,
                'stok'          => 100,
                'stok_minimum'  => 20,
                'keterangan'    => 'Semen 40 kg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_barang'   => 'BRG-002',
                'nama_barang'   => 'Pasir Bangunan',
                'id_kategori'   => 1,
                'satuan'        => 'kubik',
                'harga_beli'    => 150000,
                'harga_jual'    => 185000,
                'stok'          => 50,
                'stok_minimum'  => 10,
                'keterangan'    => 'Pasir halus untuk plester',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_barang'   => 'BRG-003',
                'nama_barang'   => 'Bata Merah',
                'id_kategori'   => 1,
                'satuan'        => 'buah',
                'harga_beli'    => 700,
                'harga_jual'    => 900,
                'stok'          => 5000,
                'stok_minimum'  => 500,
                'keterangan'    => 'Bata merah standar',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_barang'   => 'BRG-004',
                'nama_barang'   => 'Cat Tembok Putih',
                'id_kategori'   => 2,
                'satuan'        => 'kaleng',
                'harga_beli'    => 85000,
                'harga_jual'    => 110000,
                'stok'          => 60,
                'stok_minimum'  => 10,
                'keterangan'    => 'Cat tembok 5 kg',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'kode_barang'   => 'BRG-005',
                'nama_barang'   => 'Paku 5 cm',
                'id_kategori'   => 3,
                'satuan'        => 'kg',
                'harga_beli'    => 18000,
                'harga_jual'    => 25000,
                'stok'          => 200,
                'stok_minimum'  => 30,
                'keterangan'    => 'Paku biasa 5 cm',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('barang')->insertBatch($data);
    }
}
