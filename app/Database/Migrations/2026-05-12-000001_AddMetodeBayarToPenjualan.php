<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetodeBayarToPenjualan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('penjualan', [
            'metode_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['cod', 'qris'],
                'default'    => 'cod',
                'after'      => 'kembalian',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('penjualan', 'metode_bayar');
    }
}
