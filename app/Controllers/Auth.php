<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        $session = session();

        if ($session->get('isLoggedIn') && $session->get('authVersion') === 1) {
            return redirect()->to($this->landingPage($session->get('role')));
        }

        $session->remove(['id', 'username', 'role', 'isLoggedIn', 'authVersion']);

        return view('auth/login');
    }

    public function login()
    {
        return $this->index();
    }

    public function process()
    {
        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $user = (new UserModel())->where('username', $username)->first();

        if ($username === '' || $password === '' || $user === null || ! password_verify($password, $user['password'])) {
            return redirect()->to('/login')->with('error', 'Username atau password tidak valid.');
        }

        $session = session();
        $session->regenerate();
        $session->set([
            'id'         => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'photo'    => $user['photo'],
            'isLoggedIn' => true,
            'authVersion' => 1,
        ]);

        return redirect()->to($this->landingPage($user['role']));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    private function landingPage(?string $role): string
    {
        return $role === 'admin' ? '/admin/dashboard' : '/admin/orders';
    }
}
