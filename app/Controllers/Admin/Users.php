<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // List Users
    public function index()
    {
        $data = [
            'title' => 'Kelola Partners / Users - BrewNest Coffee',
            'users' => $this->userModel->findAll(),
            'isOwner' => session()->get('role') === 'staff',
        ];

        return view('admin/users/index', $data);
    }

    // Form Tambah User
    public function create()
    {
        $data = [
            'title' => 'Tambah Partner / User Baru',
            'isOwner' => session()->get('role') === 'staff',
        ];

        return view('admin/users/create', $data);
    }

    // Simpan User Baru
    public function store()
    {
        $role = (string) $this->request->getPost('role');
        if (!in_array($role, ['admin', 'staff'], true)) {
            return redirect()->back()->withInput()->with('error', 'Role pengguna tidak valid.');
        }
        if ($role === 'staff' && session()->get('role') !== 'staff') {
            return redirect()->to('/admin/users')->with('error', 'Hanya Owner yang dapat menambahkan Owner baru.');
        }

        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,staff]',
            'photo'    => 'is_image[photo]|max_size[photo,2048]|mime_in[photo,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil file foto jika ada
        $filePhoto = $this->request->getFile('photo');
        $photoName = null;

        if ($filePhoto && $filePhoto->isValid() && !$filePhoto->hasMoved()) {
            $photoName = $filePhoto->getRandomName();
            $filePhoto->move(FCPATH . 'uploads/users', $photoName);
        }

        $this->userModel->save([
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $role,
            'photo'    => $photoName,
        ]);

        return redirect()->to('/admin/users')->with('success', 'User berhasil ditambahkan!');
    }

    // Form Edit User
    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }
        if ($user['role'] === 'staff' && session()->get('role') !== 'staff') {
            return redirect()->to('/admin/users')->with('error', 'Hanya Owner yang dapat mengedit akun Owner.');
        }

        $data = [
            'title' => 'Edit Partner / User',
            'user'  => $user,
            'isOwner' => session()->get('role') === 'staff',
        ];

        return view('admin/users/edit', $data);
    }

    // Update User
    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }
        if ($user['role'] === 'staff' && session()->get('role') !== 'staff') {
            return redirect()->to('/admin/users')->with('error', 'Hanya Owner yang dapat mengedit akun Owner.');
        }

        $role = (string) $this->request->getPost('role');
        if (!in_array($role, ['admin', 'staff'], true)) {
            return redirect()->back()->withInput()->with('error', 'Role pengguna tidak valid.');
        }
        if ($role === 'staff' && session()->get('role') !== 'staff') {
            return redirect()->to('/admin/users')->with('error', 'Hanya Owner yang dapat mengubah role menjadi Owner.');
        }

        $dataUpdate = [
            'id'       => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $role,
        ];

        // Jika password diisi, lakukan update password
        if ($this->request->getPost('password')) {
            $dataUpdate['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Cek apakah ada file foto baru yang di-upload
        $filePhoto = $this->request->getFile('photo');
        if ($filePhoto && $filePhoto->isValid() && !$filePhoto->hasMoved()) {
            $photoName = $filePhoto->getRandomName();
            $filePhoto->move(FCPATH . 'uploads/users', $photoName);

            // Hapus foto lama di folder jika ada
            if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) {
                unlink(FCPATH . 'uploads/users/' . $user['photo']);
            }

            $dataUpdate['photo'] = $photoName;
        }

        $this->userModel->save($dataUpdate);

        return redirect()->to('/admin/users')->with('success', 'User berhasil diperbarui!');
    }

    // Hapus User
    public function delete($id)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
        }

        // Proteksi 1: Mencegah admin menghapus akunnya sendiri yang sedang digunakan
        if ((string) session()->get('id') === (string) $id) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user['role'] === 'staff' && session()->get('role') !== 'staff') {
            return redirect()->to('/admin/users')->with('error', 'Hanya Owner yang dapat menghapus akun Owner.');
        }

        // Hapus file foto fisik dari folder jika ada
        if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) {
            unlink(FCPATH . 'uploads/users/' . $user['photo']);
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
    }
}
