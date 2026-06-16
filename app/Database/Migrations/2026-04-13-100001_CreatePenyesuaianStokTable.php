<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenyesuaianStokTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'               => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'no_penyesuaian'   => ['type' => 'VARCHAR', 'constraint' => 50, 'unique' => true],
            'id_barang'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'stok_sebelum'     => ['type' => 'INT', 'constraint' => 11],
            'stok_sesudah'     => ['type' => 'INT', 'constraint' => 11],
            'selisih'          => ['type' => 'INT', 'constraint' => 11],
            'alasan'           => ['type' => 'VARCHAR', 'constraint' => 255],
            'tanggal'          => ['type' => 'DATE'],
            'keterangan'       => ['type' => 'TEXT', 'null' => true],
            'created_at'       => ['type' => 'DATETIME', 'null' => true],
            'updated_at'       => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('penyesuaian_stok');
    }

    public function down()
    {
        $this->forge->dropTable('penyesuaian_stok', true);
    }
}
