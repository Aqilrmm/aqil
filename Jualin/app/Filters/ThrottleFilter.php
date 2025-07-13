<?php
namespace App\Filters;


use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil alamat IP pengguna
        $ip = $request->getIPAddress();

        // Gunakan service cache bawaan CI4
        $cache = \Config\Services::cache();

        // Ambil jumlah percobaan dari cache (default 0 jika tidak ada)
        $attempts = $cache->get("throttle_{$ip}") ?? 0;

        // Jika lebih dari atau sama dengan 5 percobaan dalam 5 menit, blokir
        if ($attempts >= 5) {
            return redirect()->to('/blocked')->with('error', 'Terlalu banyak percobaan. Coba lagi nanti.');
        }

        // Tambahkan 1 percobaan, dan simpan dengan masa berlaku 300 detik (5 menit)
        $cache->save("throttle_{$ip}", $attempts + 1, 300);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak digunakan, tapi bisa ditambahkan misalnya untuk logging, audit, dll.
    }
}
