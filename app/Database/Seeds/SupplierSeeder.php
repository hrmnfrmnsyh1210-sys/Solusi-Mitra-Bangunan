<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_supplier' => 'PT Semen Indonesia',
                'no_hp'         => '021-12345678',
                'alamat'        => 'Jl. Industri No. 1, Jakarta',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_supplier' => 'UD Bahan Bangunan Maju',
                'no_hp'         => '0812-9999-1234',
                'alamat'        => 'Jl. Raya Bangunan No. 45, Surabaya',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'nama_supplier' => 'Toko Besi Jaya',
                'no_hp'         => '0856-1234-5678',
                'alamat'        => 'Jl. Pasar Besi No. 12, Bandung',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('supplier')->insertBatch($data);
    }
}
