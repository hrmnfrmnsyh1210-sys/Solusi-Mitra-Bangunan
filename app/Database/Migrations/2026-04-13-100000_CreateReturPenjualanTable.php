<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReturPenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'no_retur'       => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'id_penjualan'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'tanggal_retur'  => ['type' => 'DATE'],
            'alasan'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'keterangan'     => ['type' => 'TEXT', 'null' => true],
            'created_at'     => ['type' => 'DATETIME', 'null' => true],
            'updated_at'     => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('retur_penjualan');

        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_retur'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'id_barang'     => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah'        => ['type' => 'INT', 'constraint' => 11],
            'harga_jual'    => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'subtotal'      => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('detail_retur_penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('detail_retur_penjualan', true);
        $this->forge->dropTable('retur_penjualan', true);
    }
}
