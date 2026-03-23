<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_detail_id',
        'order_id',
        'item_id',
        'promotion_price',
        'quantity',
        'remark',
        'is_ordered',
        'is_foc',
        'order_type',
        'ordered_by',
        'created_at',
    ];
}
