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
            'users' => $this->userModel->findAll()
        ];

        return view('admin/users/index', $data);
    }

    // Form Tambah User
    public function create()
    {
        $data = [
            'title' => 'Tambah Partner / User Baru'
        ];

        return view('admin/users/create', $data);
    }

    // Simpan User Baru
    public function store()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required',
            'photo'    => 'is_image[photo]|max_size[photo,2048]|mime_in[photo,image/jpg,image/jpeg,image/png]' // Validasi foto (opsional tapi aman)
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
            'role'     => $this->request->getPost('role'),
            'photo'    => $photoName, // Simpan nama file foto ke database
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

        $data = [
            'title' => 'Edit Partner / User',
            'user'  => $user
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

        $dataUpdate = [
            'id'       => $id,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
            'role'     => $this->request->getPost('role'),
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

        // Proteksi: Mencegah admin menghapus akunnya sendiri yang sedang digunakan
        if (session()->get('user_id') == $id) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        // Hapus file foto fisik dari folder jika ada
        if (!empty($user['photo']) && file_exists(FCPATH . 'uploads/users/' . $user['photo'])) {
            unlink(FCPATH . 'uploads/users/' . $user['photo']);
        }

        $this->userModel->delete($id);

        return redirect()->to('/admin/users')->with('success', 'User berhasil dihapus!');
    }
}
