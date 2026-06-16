<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = ['google_id', 'nama', 'email', 'foto', 'no_hp', 'alamat'];
    protected $useTimestamps    = true;
    protected $dateFormat       = 'datetime';
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function findByGoogleId(string $googleId): ?array
    {
        return $this->where('google_id', $googleId)->first();
    }

    public function findOrCreateFromGoogle(array $googleUser): array
    {
        $customer = $this->findByGoogleId($googleUser['sub']);

        if ($customer) {
            $this->update($customer['id'], [
                'nama' => $googleUser['name'],
                'foto' => $googleUser['picture'] ?? null,
            ]);
            return $this->find($customer['id']);
        }

        $this->insert([
            'google_id' => $googleUser['sub'],
            'nama'      => $googleUser['name'],
            'email'     => $googleUser['email'],
            'foto'      => $googleUser['picture'] ?? null,
        ]);

        return $this->find($this->getInsertID());
    }
}
