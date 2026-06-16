<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturPenjualanModel extends Model
{
    protected $table            = 'retur_penjualan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'no_retur', 'id_penjualan', 'tanggal_retur', 'alasan', 'keterangan',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'tanggal_retur' => 'required|valid_date',
        'alasan'        => 'required|min_length[5]',
    ];
    protected $validationMessages = [
        'alasan' => ['required' => 'Alasan retur wajib diisi.', 'min_length' => 'Alasan minimal 5 karakter.'],
    ];

    public function getAllWithDetail()
    {
        return $this->select('retur_penjualan.*, penjualan.no_transaksi,
                COALESCE(SUM(detail_retur_penjualan.subtotal), 0) as total_retur,
                COUNT(detail_retur_penjualan.id) as jumlah_item')
            ->join('penjualan', 'penjualan.id = retur_penjualan.id_penjualan', 'left')
            ->join('detail_retur_penjualan', 'detail_retur_penjualan.id_retur = retur_penjualan.id', 'left')
            ->groupBy('retur_penjualan.id')
            ->orderBy('retur_penjualan.tanggal_retur', 'DESC')
            ->findAll();
    }

    public function generateNoRetur()
    {
        $prefix = 'RT-' . date('Ymd') . '-';
        $last   = $this->like('no_retur', $prefix, 'after')
                       ->orderBy('id', 'DESC')
                       ->first();
        if (!$last) return $prefix . '001';
        $num = (int) substr($last['no_retur'], -3);
        return $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
