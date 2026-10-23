<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ReviewModel;

class Orders extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;
    protected $reviewModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
        $this->reviewModel = new ReviewModel();
    }

    public function index()
    {
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        foreach ($orders as &$order) {
            // Ambil rincian item menu pesanan
            $order['items'] = $this->orderItemModel
                ->select('order_items.*, menus.title as menu_name')
                ->join('menus', 'menus.id = order_items.menu_id', 'left')
                ->where('order_items.order_id', $order['id'])
                ->findAll();

            $order['review'] = $this->findReview($order);
        }
        unset($order);

        $data['orders'] = $orders;

        return view('admin/orders/history', $data);
    }

    public function detail($id)
    {
        $order = $this->orderModel->find($id);

        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Pesanan tidak ditemukan.');
        }

        // Ambil rincian item menu pesanan
        $items = $this->orderItemModel
            ->select('order_items.*, menus.title as menu_name')
            ->join('menus', 'menus.id = order_items.menu_id', 'left')
            ->where('order_items.order_id', $order['id'])
            ->findAll();

        $review = $this->findReview($order);

        $data = [
            'order' => $order,
            'items' => $items,
            'review' => $review,
        ];

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

    private function findReview(array $order): ?array
    {
        $review = $this->reviewModel->where('order_id', $order['id'])->first();
        if (
            !$review
            && !empty($order['order_number'])
            && db_connect()->fieldExists('order_number', 'reviews')
        ) {
            $review = $this->reviewModel->where('order_number', $order['order_number'])->first();
        }

        return $this->prepareReview($review);
    }

    private function prepareReview(?array $review): ?array
    {
        if (!$review) {
            return null;
        }

        $review['comment'] = $review['comment'] ?? $review['review'] ?? null;
        $review['recommend'] = $review['recommend'] ?? null;
        $review['is_anonymous'] = $review['is_anonymous'] ?? 0;
        foreach (['aspects', 'items', 'tags'] as $field) {
            $decoded = json_decode($review[$field] ?? '', true);
            $review[$field] = is_array($decoded) ? $decoded : [];
        }

        return $review;
    }
}
