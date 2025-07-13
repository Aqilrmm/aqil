<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;

class AccountModel extends Model
{
    // Nama tabel yang digunakan oleh model ini.
    protected $table = 'accounts';

    // Primary key tabel. Wajib disesuaikan jika bukan 'id'.
    protected $primaryKey = 'AccountId';

    // Auto-increment dinonaktifkan karena kita akan pakai UUID.
    protected $useAutoIncrement = false;

    // Tipe data hasil query
    protected $returnType = 'array';

    // Tidak menggunakan soft deletes
    protected $useSoftDeletes = true;

    // Melindungi field agar hanya field tertentu yang bisa diisi (whitelisting)
    protected $protectFields = true;

    // Field yang diizinkan untuk diisi
    protected $allowedFields = [
        'AccountId',
        'Email',
        'Password',
        'FullName',
        'PhoneNumber',
        'BirthDate',
        'RoleId',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    // Menggunakan timestamp untuk created_at dan updated_at
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';



    // allow callbacks di set true karena kita akan menggunakan callback
    protected $allowCallbacks = true;

    // Callback sebelum dan sesudah insert
    protected $beforeInsert = ['generateUUID', 'hashPassword'];
    protected $afterInsert = [];

    // Callback sebelum dan sesudah update
    protected $beforeUpdate = ['hashPassword'];
    protected $afterUpdate = [];

    // Callback sebelum dan sesudah find
    protected $beforeFind = [];
    protected $afterFind = [];

    // Callback sebelum dan sesudah delete
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Callback untuk hash password sebelum disimpan ke database.
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['Password'])) {
            $data['data']['Password'] = password_hash($data['data']['Password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    /**
     * Callback untuk membuat UUID secara otomatis sebelum insert.
     * Mengisi AccountId dengan UUID v4 jika belum diset.
     */
    protected function generateUUID(array $data)
    {
        if (empty($data['data']['AccountId'])) {
            $data['data']['AccountId'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function isEmailRegistered(string $email): bool
    {
        return $this->where('Email', $email)->countAllResults() > 0;
    }

    public function getAccountById(string $AccountId): ?array
    {
        
        return $this->select('AccountId, Email, FullName, PhoneNumber, BirthDate')
            ->where('AccountId', $AccountId)
            ->first();
    }
}
