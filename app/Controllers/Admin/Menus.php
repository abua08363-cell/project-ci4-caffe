<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;

class Menus extends BaseController
{
    protected $menuModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Menu - BrewNest Coffee',
            'menus' => $this->menuModel->findAll()
        ];

        return view('admin/menu/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Menu - BrewNest Coffee'
        ];

        return view('admin/menu/create', $data);
    }

    public function store()
    {
        $fileImage = $this->request->getFile('image');
        $imageName = null;

        if ($fileImage && $fileImage->isValid() && !$fileImage->hasMoved()) {
            $imageName = $fileImage->getRandomName();
            $fileImage->move('uploads/menus', $imageName);
        }

        $this->menuModel->save([
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'price'       => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'image'       => $imageName
        ]);

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Menu - BrewNest Coffee',
            'menu'  => $this->menuModel->find($id)
        ];

        return view('admin/menu/edit', $data);
    }

    public function update($id)
    {
        $fileImage = $this->request->getFile('image');
        $menuLama = $this->menuModel->find($id);
        $imageName = $menuLama['image'];

        if ($fileImage && $fileImage->isValid() && !$fileImage->hasMoved()) {
            $imageName = $fileImage->getRandomName();
            $fileImage->move('uploads/menus', $imageName);

            if ($menuLama['image'] && file_exists('uploads/menus/' . $menuLama['image'])) {
                unlink('uploads/menus/' . $menuLama['image']);
            }
        }

        $this->menuModel->update($id, [
            'title'       => $this->request->getPost('title'),
            'category'    => $this->request->getPost('category'),
            'price'       => $this->request->getPost('price'),
            'description' => $this->request->getPost('description'),
            'image'       => $imageName
        ]);

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil diperbarui!');
    }

    public function delete($id)
    {
        $menu = $this->menuModel->find($id);

        if ($menu) {
            if ($menu['image'] && file_exists('uploads/menus/' . $menu['image'])) {
                unlink('uploads/menus/' . $menu['image']);
            }
            $this->menuModel->delete($id);
        }

        return redirect()->to('/admin/menus')->with('success', 'Menu berhasil dihapus!');
    }
}
