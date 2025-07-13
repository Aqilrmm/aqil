<?php

namespace App\Controllers;

use App\Models\AccountModel;
use App\Models\StoreModel;

class Home extends BaseController
{
    protected $accountModel;
    protected $personalModel;
    protected $addressModel;
    protected $storeModel;

    public function __construct()
    {
        $this->accountModel = new AccountModel();
        $this->storeModel = new StoreModel();
    }
    public function index(): string
    {
        return view('welcome_message');
    }
    public function registrasi(): string
    {
        return view('auth/registrasi');
    }
    public function prosesRegistrasi(): \CodeIgniter\HTTP\ResponseInterface
    {
        $data = $this->request->getJSON(true);

        $account = [
            'Email' => $data['account']['email'],
            'Password' => $data['account']['password'],
            'FullName' => $data['personal']['nama-lengkap'],
            'PhoneNumber' => $data['personal']['nomor-telepon'],
            'BirthDate' => $data['personal']['tanggal-lahir'],
            'RoleId' => 2,
        ];

        if ($this->accountModel->where('Email', $data['account']['email'])->first()) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email sudah terdaftar'
            ]);
        }

        $this->accountModel->insert($account);
        $newAccount = $this->accountModel->where('Email', $account['Email'])->first();

        $store = [
            'AccountId' => $newAccount['AccountId'],
            'StoreName' => $data['store']['nama-toko'],
            'StoreCategory' => $data['store']['kategori-toko'],
            'StoreDescription' => $data['store']['deskripsi-toko'],
            'StoreAddress' => $data['address']['alamat-lengkap'],
            'StoreProvince' => $data['address']['provinsi'],
            'StoreCity' => $data['address']['kota'],
            'StoreZipCode' => $data['address']['kode-pos'],
        ];

        $this->storeModel->insert($store);
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Akun berhasil dibuat'
        ]);
    }



    public function login(): string
    {
        return view('auth/login');
    }


    public function prosesLogin(): \CodeIgniter\HTTP\ResponseInterface
    {


        // Ambil data dari FormData (POST biasa, bukan JSON)
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validasi input dasar
        if (empty($email) || empty($password)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email dan password wajib diisi'
            ]);
        }

        // Cari user
        $akun = $this->accountModel->where('Email', $email)->first();

        if (! $akun || ! password_verify($password, $akun['Password'])) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Email atau password salah'
            ]);
        }

        // Simpan sesi login

        session()->set([
            'isLoggedIn'     => true,
            'AccountId'     => $akun['AccountId'],
            'role'           => $akun['RoleId'],
            'ip_address'     => $_SERVER['REMOTE_ADDR'],
            'user_agent'     => $_SERVER['HTTP_USER_AGENT'],
        ]);
        
        session()->setFlashdata('welcome', 'Selamat datang di Jualin App!');
        return $this->response->setJSON([
            'status' => 'success',
            'message' => session()->get(),
        ]);
    }

    public function logout()
    {
        // Hapus sesi
        session()->destroy();

        return redirect()->to('/login')->with('message', 'Anda telah keluar.');
    }

    public function forgot(): string
    {
        return view('auth/forgot');
    }
    public function reset(): string
    {
        return view('auth/reset');
    }
}
