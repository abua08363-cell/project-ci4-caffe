<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\OrderModel;

class Dashboard extends BaseController
{
    public function index()
    {
        if (!in_array(session()->get('role'), ['admin', 'staff'], true)) {
            return redirect()->to('/login')->with('error', 'Akses ditolak.');
        }

        $menuModel     = new MenuModel();
        $userModel     = new UserModel();
        $categoryModel = new CategoryModel();
        $orderModel    = new OrderModel();

        $data = [
            'title'          => 'Dashboard - BrewNest Coffee',
            'total_menu'     => $menuModel->countAllResults(),
            'total_users'    => $userModel->countAllResults(),
            'total_category' => $categoryModel->countAllResults(),
            'total_orders'   => $orderModel->countAllResults(),
            // Mengambil 5 pesanan terbaru untuk ringkasan di dashboard
            'recent_orders'  => $orderModel->orderBy('created_at', 'DESC')->findAll(5),
        ];

        return view('admin/dashboard/index', $data);
    }
}
