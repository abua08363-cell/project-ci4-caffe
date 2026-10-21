<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    // Daftarkan semua kolom agar data dari kasir tidak ditolak oleh model
    protected $allowedFields = [
        'user_id',
        'order_number',
        'customer_name',
        'table_number',
        'subtotal',
        'tax',
        'total',
        'total_price',
        'payment_method',
        'payment_status',
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
