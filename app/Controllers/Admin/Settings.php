<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SettingModel; // Pastikan model ini ada, atau kita simpan langsung

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
        $userId = session()->get('user_id') ?? 1;

        // Ambil pengaturan kedai dari database (Contoh menggunakan model Setting atau query builder)
        $settingModel = new \App\Models\SettingModel();
        $settingsRaw = $settingModel->findAll();

        // Ubah format array database menjadi associative key => value agar mudah dipanggil di view
        $settings = [];
        foreach ($settingsRaw as $row) {
            $settings[$row['key']] = $row['value'];
        }

        $data = [
            'title'    => 'Pengaturan Sistem - BrewNest Coffee',
            'user'     => $this->userModel->find($userId),
            'settings' => $settings
        ];

        return view('admin/settings/index', $data);
    }

    // Update Pengaturan Akun & Toko
    public function update()
    {
        $userId = session()->get('user_id') ?? 1;

        $rules = [
            'username'     => 'required|min_length[3]',
            'email'        => 'required|valid_email',
            'store_status' => 'required',
            'store_name'   => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 1. Update Data Akun Admin
        $dataUpdate = [
            'id'       => $userId,
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        $newPassword = $this->request->getPost('password');
        if (!empty($newPassword)) {
            $dataUpdate['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $this->userModel->save($dataUpdate);

        // 2. Update Pengaturan Kedai (Status, Jam, Pajak, Profil, Pembayaran)
        $settingModel = new \App\Models\SettingModel();

        $configs = [
            'store_status'   => $this->request->getPost('store_status'),
            'opening_hours'  => $this->request->getPost('opening_hours'),
            'tax_percentage' => $this->request->getPost('tax_percentage'),
            'store_name'     => $this->request->getPost('store_name'),
            'store_tagline'  => $this->request->getPost('store_tagline'),
            'phone_number'   => $this->request->getPost('phone_number'),
            'contact_email'  => $this->request->getPost('contact_email'),
            'store_address'  => $this->request->getPost('store_address'),
            'pay_qris'       => $this->request->getPost('pay_qris') ? 1 : 0,
            'pay_cash'       => $this->request->getPost('pay_cash') ? 1 : 0,
            'pay_transfer'   => $this->request->getPost('pay_transfer') ? 1 : 0,
        ];

        // Loop dan simpan ke tabel settings berdasarkan key
        foreach ($configs as $key => $value) {
            // Cek apakah key sudah ada di database
            $exists = $settingModel->where('key', $key)->first();
            if ($exists) {
                $settingModel->where('key', $key)->set(['value' => $value])->update();
            } else {
                $settingModel->insert(['key' => $key, 'value' => $value]);
            }
        }

        return redirect()->to('/admin/settings')->with('success', 'Pengaturan sistem dan akun berhasil diperbarui!');
    }
}
