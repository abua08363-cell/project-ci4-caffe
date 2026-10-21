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
        // Pengecekan Hak Akses (RBAC): Pastikan hanya 'admin' yang bisa akses penuh
        if (session()->get('role') !== 'admin') {
            return redirect()->to('/admin/orders')->with('error', 'Dashboard hanya tersedia untuk Admin.');
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
