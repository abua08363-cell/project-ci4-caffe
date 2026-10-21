<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Cek apakah user sudah login
        if (!$session->get('isLoggedIn') || $session->get('authVersion') !== 1) {
            $session->remove(['id', 'username', 'role', 'isLoggedIn', 'authVersion']);

            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. Jika rute membutuhkan role khusus (misal: 'admin')
        if (!empty($arguments)) {
            $userRole = $session->get('role');

            // Jika role user tidak ada di dalam daftar argumen yang diizinkan
            if (!in_array($userRole, $arguments)) {
                $redirectTo = $userRole === 'staff' ? '/admin/orders' : '/admin/dashboard';

                return redirect()->to($redirectTo)->with('error', 'Akses ditolak. Fitur ini khusus Admin.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
