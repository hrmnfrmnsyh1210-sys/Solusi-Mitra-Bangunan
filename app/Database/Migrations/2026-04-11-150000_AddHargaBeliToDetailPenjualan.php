<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHargaBeliToDetailPenjualan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('detail_penjualan', [
            'harga_beli' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'after'      => 'id_barang',
                'comment'    => 'snapshot harga beli saat transaksi, untuk hitung keuntungan',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('detail_penjualan', 'harga_beli');
    }
}
