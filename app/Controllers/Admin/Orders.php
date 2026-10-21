<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    public function index()
    {
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();

        foreach ($orders as &$order) {
            $order['items'] = $this->orderItemModel
                ->select('order_items.*, menus.title as menu_name')
                ->join('menus', 'menus.id = order_items.menu_id', 'left')
                ->where('order_items.order_id', $order['id'])
                ->findAll();
        }

        $data['orders'] = $orders;

        return view('admin/orders/history', $data);
    }

    public function detail($id)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $order['items'] = $this->orderItemModel
            ->select('order_items.*, menus.title as menu_name')
            ->join('menus', 'menus.id = order_items.menu_id', 'left')
            ->where('order_items.order_id', $order['id'])
            ->findAll();

        $data['order'] = $order;
        return view('admin/orders/detail', $data);
    }

    public function updateStatus($id)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        $this->orderModel->update($id, [
            'payment_status' => 'paid'
        ]);

        return redirect()->to('/admin/orders')->with('success', 'Status pesanan berhasil diperbarui!');
    }
}
