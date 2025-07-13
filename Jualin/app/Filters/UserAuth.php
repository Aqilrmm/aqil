<?php
// app/Filters/SessionCheckFilter.php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class UserAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Sesi tidak ditemukan.');
        }
        // jika role bukan 2 (user), redirect ke login
        if($session->get('role') !== '2') {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        if ($session->get('ip_address') !== $_SERVER['REMOTE_ADDR']) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'IP tidak valid.');
        }

        if ($session->get('user_agent') !== $_SERVER['HTTP_USER_AGENT']) {
            $session->destroy();
            return redirect()->to('/login')->with('error', 'User agent tidak cocok.');
        }


    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu diisi
    }
}

