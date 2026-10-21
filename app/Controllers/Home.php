<?php

namespace App\Controllers;

use App\Models\MenuModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $menuModel     = new MenuModel();
        $categoryModel = new CategoryModel();

        $menus = $menuModel->findAll();

        // TEST DEBUG: Cek isi data dari database
        // dd($menus);

        $data = [
            'title'      => 'BrewNest Coffee | Temukan Kopi Favoritmu',
            'menus'      => $menus,
            'categories' => $categoryModel->findAll()
        ];

        return view('welcome_message', $data);
    }
}
