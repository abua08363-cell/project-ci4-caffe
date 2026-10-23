<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table            = 'reviews';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'order_id',
        'order_number',
        'customer_name',
        'rating',
        'review',
        'aspects',
        'items',
        'tags',
        'comment',
        'recommend',
        'is_anonymous',
        'is_published',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps    = false;
}
