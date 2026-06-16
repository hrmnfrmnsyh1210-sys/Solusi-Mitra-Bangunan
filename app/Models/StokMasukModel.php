<?php

namespace App\Models;

use CodeIgniter\Model;

class StokMasukModel extends Model
{
    protected $table            = 'stok_masuk';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'no_transaksi', 'id_supplier', 'tanggal_masuk',
        'total_harga', 'keterangan',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'id_supplier'   => 'permit_empty|integer',
        'tanggal_masuk' => 'required|valid_date',
        'total_harga'   => 'permit_empty|decimal',
    ];
    protected $validationMessages = [
        'tanggal_masuk' => [
            'required'   => 'Tanggal masuk wajib diisi.',
            'valid_date' => 'Format tanggal tidak valid.',
        ],
    ];
    protected $skipValidation = false;

    // Ambil data stok masuk beserta nama supplier
    public function getWithSupplier($id = null)
    {
        $builder = $this->select('stok_masuk.*, supplier.nama_supplier')
                        ->join('supplier', 'supplier.id = stok_masuk.id_supplier', 'left');

        if ($id !== null) {
            return $builder->where('stok_masuk.id', $id)->first();
        }

        return $builder->orderBy('stok_masuk.tanggal_masuk', 'DESC')->findAll();
    }

    // Generate nomor transaksi otomatis: SM-YYYYMMDD-XXX
    public function generateNoTransaksi()
    {
        $prefix = 'SM-' . date('Ymd') . '-';
        $last   = $this->like('no_transaksi', $prefix, 'after')
                       ->orderBy('id', 'DESC')
                       ->first();

        if (!$last) {
            return $prefix . '001';
        }

        $num = (int) substr($last['no_transaksi'], -3);
        return $prefix . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
    }
}
