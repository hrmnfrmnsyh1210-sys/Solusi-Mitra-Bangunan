<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGambarToBarang extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang', [
            'gambar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'keterangan',
                'comment'    => 'nama file gambar di public/uploads/barang/',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', 'gambar');
    }
}
