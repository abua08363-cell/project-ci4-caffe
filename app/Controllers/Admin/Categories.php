<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    // Menampilkan daftar kategori
    public function index()
    {
        $data = [
            'title'      => 'Kelola Kategori - BrewNest Coffee',
            'categories' => $this->categoryModel->findAll()
        ];

        return view('admin/categories/index', $data);
    }

    // Form Tambah Kategori
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori Baru'
        ];

        return view('admin/categories/create', $data);
    }

    // Simpan Kategori Baru
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|is_unique[categories.name]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Form Edit Kategori
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Kategori',
            'category' => $category
        ];

        return view('admin/categories/edit', $data);
    }

    // Update Kategori
    public function update($id)
    {
        $rules = [
            'name' => 'required|min_length[3]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'id'   => $id,
            'name' => $this->request->getPost('name')
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Hapus Kategori
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/admin/categories')->with('error', 'Kategori tidak ditemukan.');
        }

        $this->categoryModel->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus!');
    }
}
