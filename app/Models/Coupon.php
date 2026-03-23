<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'coupon_code',
        'coupon_name',
        'discount_type',
        'amount_discount',
        'percent_discount',
        'min_order_amount',
        'expire_date',
        'is_used',
        'is_discontinued',
        'is_deleted',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
