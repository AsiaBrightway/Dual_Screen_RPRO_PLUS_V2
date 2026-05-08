<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeletedOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'deleted_orders_id',
        'order_id',
        'item_id',
        'promotion_price',
        'quantity',
        'remark',
        'is_ordered',
        'is_foc',
        'order_type',
        'order_time',
        'ordered_by',
        'created_at',
        'table_id',
        'deleted_by'
    ];
}
