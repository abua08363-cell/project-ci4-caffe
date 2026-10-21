<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Checkout extends BaseController
{
    protected $orderModel;
    protected $orderItemModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderItemModel = new OrderItemModel();
    }

    // Halaman Pilih Metode Pembayaran
    public function index()
    {
        $data = [
            'title' => 'Metode Pembayaran - BrewNest Coffee'
        ];

        return view('cart/checkout', $data);
    }

    // Proses Simpan Pesanan ke Database
    public function process()
    {
        $json = $this->request->getJSON(true);

        if (!$json || empty($json['cart'])) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Keranjang kosong!']);
        }

        $customerName = $json['customer_name'] ?? 'Tamu';
        $tableNumber = $json['table_number'] ?? 'Takeaway';
        $paymentMethod = $json['payment_method'] ?? 'Tunai';
        $cartItems = $json['cart'];

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] * $item['qty']);
        }

        $tax = $subtotal * 0.11; // Pajak 11%
        $total = $subtotal + $tax;

        // Buat nomor pesanan unik
        $orderNumber = '#BRW' . rand(10000, 99999);

        // Simpan ke tabel orders
        $orderId = $this->orderModel->insert([
            'order_number'   => $orderNumber,
            'customer_name'  => $customerName,
            'table_number'   => $tableNumber,
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
            'payment_method' => $paymentMethod,
            'payment_status' => 'paid'
        ], true);

        // Simpan rincian item ke tabel order_items
        foreach ($cartItems as $item) {
            $this->orderItemModel->insert([
                'order_id' => $orderId,
                'menu_id'  => $item['id'],
                'qty'      => $item['qty'],
                'price'    => $item['price'],
                'subtotal' => $item['price'] * $item['qty']
            ]);
        }

        return $this->response->setJSON([
            'status'       => 'success',
            'order_number' => $orderNumber,
            'redirect_url' => base_url('checkout/success/' . urlencode($orderNumber))
        ]);
    }

    // Halaman Pembayaran Berhasil
    public function success($orderNumber = null)
    {
        if (!$orderNumber) {
            return redirect()->to('/');
        }

        $orderNumber = urldecode($orderNumber);
        $order = $this->orderModel->where('order_number', $orderNumber)->first();

        if (!$order) {
            return redirect()->to('/')->with('error', 'Pesanan tidak ditemukan.');
        }

        $data = [
            'title' => 'Pembayaran Berhasil - BrewNest Coffee',
            'order' => $order
        ];

        return view('cart/success', $data);
    }
}
