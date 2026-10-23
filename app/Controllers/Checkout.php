<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\ReviewModel;

class Checkout extends BaseController
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

    // Method untuk Menangani Pengiriman Ulasan (POST)
    public function submitReview($orderNumber = null)
    {
        if (!$orderNumber) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ]);
        }

        $orderNumber = urldecode($orderNumber);
        $order = $this->orderModel->where('order_number', $orderNumber)->first();
        if (!$order) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ]);
        }

        $data = $this->request->getJSON(true) ?? [];
        $rating = filter_var($data['rating'] ?? null, FILTER_VALIDATE_INT);
        if ($rating === false || $rating < 1 || $rating > 5) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => 'error',
                'message' => 'Pilih rating bintang terlebih dahulu.',
            ]);
        }

        $existingReview = $this->reviewModel
            ->where('order_id', $order['id'])
            ->first();

        if ($existingReview) {
            return $this->response->setStatusCode(409)->setJSON([
                'status' => 'error',
                'message' => 'Pesanan ini sudah pernah diulas. Terima kasih!',
            ]);
        }

        $validRating = static function ($value): ?int {
            $value = filter_var($value, FILTER_VALIDATE_INT);
            return $value !== false && $value >= 1 && $value <= 5 ? $value : null;
        };
        $aspects = [];
        foreach ((array) ($data['aspects'] ?? []) as $key => $value) {
            if (in_array($key, ['rasa', 'pelayanan', 'kecepatan', 'suasana'], true)
                && ($value = $validRating($value)) !== null) {
                $aspects[$key] = $value;
            }
        }

        $items = [];
        foreach ((array) ($data['items'] ?? []) as $menuId => $value) {
            if (ctype_digit((string) $menuId) && ($value = $validRating($value)) !== null) {
                $items[(int) $menuId] = $value;
            }
        }

        $allowedTags = [
            'Rasa mantap',
            'Barista ramah',
            'Penyajian cepat',
            'Tempat nyaman',
            'Harga sepadan',
            'Rasa kurang pas',
            'Penyajian lama',
            'Pelayanan kurang ramah',
            'Tempat kurang nyaman',
            'Harga kurang sepadan',
        ];
        $tags = array_values(array_intersect((array) ($data['tags'] ?? []), $allowedTags));
        $comment = mb_substr(trim(strip_tags((string) ($data['comment'] ?? ''))), 0, 300);
        $recommend = $data['recommend'] ?? null;
        if (!in_array($recommend, [null, 0, 1, '0', '1'], true)) {
            $recommend = null;
        } elseif ($recommend !== null) {
            $recommend = (int) $recommend;
        }
        $isAnonymous = !empty($data['anonymous']);

        $reviewData = [
            'order_id'      => $order['id'],
            'order_number'  => $orderNumber,
            'customer_name' => $isAnonymous ? null : ($order['customer_name'] ?? null),
            'rating'        => $rating,
            'aspects'       => $aspects ? json_encode($aspects) : null,
            'items'         => $items ? json_encode($items) : null,
            'tags'          => $tags ? json_encode($tags, JSON_UNESCAPED_UNICODE) : null,
            'comment'       => $comment !== '' ? $comment : null,
            'recommend'     => $recommend,
            'is_anonymous'  => $isAnonymous ? 1 : 0,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ];

        $db = db_connect();
        if (!$db->fieldExists('order_number', 'reviews')) {
            unset($reviewData['order_number']);
        }
        if (!$db->fieldExists('customer_name', 'reviews')) {
            unset($reviewData['customer_name']);
        }
        if (!$db->fieldExists('comment', 'reviews') && $db->fieldExists('review', 'reviews')) {
            $reviewData['review'] = $reviewData['comment'];
            unset($reviewData['comment']);
        }
        foreach (['aspects', 'items', 'tags', 'recommend', 'updated_at'] as $optionalField) {
            if (!$db->fieldExists($optionalField, 'reviews')) {
                unset($reviewData[$optionalField]);
            }
        }
        if (!$db->fieldExists('created_at', 'reviews')) {
            unset($reviewData['created_at']);
        }

        $saved = $this->reviewModel->insert($reviewData);

        if (!$saved) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Ulasan gagal disimpan. Silakan coba lagi.',
            ]);
        }

        return $this->response->setJSON([
            'status' => 'ok',
            'message' => 'Ulasan terkirim.',
        ]);
    }
}
