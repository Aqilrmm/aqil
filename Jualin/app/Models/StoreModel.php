<?php

namespace App\Models;

use CodeIgniter\Model;
use Ramsey\Uuid\Uuid;


class StoreModel extends Model
{
    protected $table      = 'stores';
    protected $primaryKey = 'StoreId';

    protected $useAutoIncrement = false;

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $protectFields = true;

    protected $allowedFields = [
    'StoreId',
    'AccountId',
    'StoreName',
    'StoreCategory',
    'StoreDescription',
    'StoreAddress',
    'StoreProvince',
    'StoreCity',
    'StoreZipCode',
    'created_at',
    'updated_at',
    'deleted_at',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdAtField  = 'created_at';
    protected $updatedAtField  = 'updated_at';
    protected $deletedAtField  = 'deleted_at';
   
    // allow callbacks di set true karena kita akan menggunakan callback
    protected $beforeInsert   = ['generateUUID'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    protected function generateUUID(array $data)
    {
        if (empty($data['data']['StoreId'])) {
            $data['data']['StoreId'] = Uuid::uuid4()->toString();
        }
        return $data;
    }

    public function getStoreByAccountId($AccountId)
    {
        return $this->where('AccountId', $AccountId)->findAll();
    }
    
}