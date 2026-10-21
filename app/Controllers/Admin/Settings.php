<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Settings extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Menampilkan Halaman Settings
    public function index()
    {
        // Ambil data admin yang sedang login (sementara ambil ID 1 atau dari session)
        $userId = session()->get('user_id') ?? 1;

        $data = [
            'title' => 'Pengaturan Sistem - BrewNest Coffee',
            'user'  => $this->userModel->find($userId)
        ];

        return view('admin/settings/index', $data);
    }

    // Update Pengaturan Akun / Toko
    public function update()
    {
        $userId = session()->get('user_id') ?? 1;

        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $dataUpdate = [
            'id'       => $userId,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        // Jika kolom password diisi, update password juga
        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $dataUpdate['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->save($dataUpdate);

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan akun berhasil diperbarui!');
    }
}
