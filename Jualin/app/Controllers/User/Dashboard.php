<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\AccountModel;
use App\Models\StoreModel;

class Dashboard extends BaseController
{
    protected $accountModel;
    protected $storeModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->storeModel = new StoreModel();
    }

    public function index()
    {
        $AccountId = session()->get('AccountId');

        $data = [
            'title' => 'Dashboard',
            'Account' => $this->accountModel->getAccountById($AccountId),

        ];
        return view('user/dashboard', $data);
    }
}
