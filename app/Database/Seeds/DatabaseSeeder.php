<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('KategoriSeeder');
        $this->call('SupplierSeeder');
        $this->call('BarangSeeder');
        $this->call('UserSeeder');
    }
}
