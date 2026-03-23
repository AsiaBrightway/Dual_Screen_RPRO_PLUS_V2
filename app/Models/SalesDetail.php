<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_id',
        'item_id',
        'batch_number',
        'quantity',
        'unit_cost',
        'sale_price',
        'promotion_price',
        'unit_id',
        'remark',
        'expire_date',
        'is_foc',
        'sale_type',
        'ordered_by',
        'order_time'
    ];
}
