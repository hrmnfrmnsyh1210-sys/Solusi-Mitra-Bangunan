<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusBayarToPenjualan extends Migration
{
    public function up()
    {
        $this->forge->addColumn('penjualan', [
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'lunas', 'batal'],
                'default'    => 'lunas',
                'after'      => 'metode_bayar',
                'comment'    => 'pending=menunggu validasi admin, lunas=terverifikasi, batal=ditolak/dibatalkan',
            ],
            'bukti_bayar' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'status_bayar',
                'comment'    => 'nama file bukti transfer QRIS di public/uploads/bukti/',
            ],
            'tanggal_bayar' => [
                'type'  => 'DATETIME',
                'null'  => true,
                'after' => 'bukti_bayar',
                'comment' => 'waktu pembayaran divalidasi lunas',
            ],
        ]);

        // Data lama dianggap sudah lunas (transaksi kasir/POS).
        $this->db->table('penjualan')->update(['status_bayar' => 'lunas'], ['status_bayar' => null]);
    }

    public function down()
    {
        $this->forge->dropColumn('penjualan', ['status_bayar', 'bukti_bayar', 'tanggal_bayar']);
    }
}
