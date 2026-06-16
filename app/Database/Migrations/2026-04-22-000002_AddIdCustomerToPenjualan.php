<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdCustomerToPenjualan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('penjualan', [
            'id_customer' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('penjualan', 'id_customer');
    }
}
