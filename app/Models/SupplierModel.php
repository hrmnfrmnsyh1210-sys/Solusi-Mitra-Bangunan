<?php

namespace App\Models;

use CodeIgniter\Model;

class SupplierModel extends Model
{
    protected $table            = 'supplier';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_supplier', 'no_hp', 'alamat'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nama_supplier' => 'required|min_length[2]|max_length[150]',
        'no_hp'         => 'permit_empty|max_length[20]',
        'alamat'        => 'permit_empty',
    ];
    protected $validationMessages = [
        'nama_supplier' => [
            'required'   => 'Nama supplier wajib diisi.',
            'min_length' => 'Nama supplier minimal 2 karakter.',
            'max_length' => 'Nama supplier maksimal 150 karakter.',
        ],
    ];
    protected $skipValidation = false;
}
