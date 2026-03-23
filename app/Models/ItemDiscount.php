<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDiscount extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'description',
        'other_description',
        'item_price',
        'buy_quantity',
        'discount_type',
        'amount_discount',
        'percent_discount',
        'promotion_price',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
        'start_date',
        'end_date',
        'start_happy_hour',
        'end_happy_hour',
        'location_id',
        'is_updated',
        'is_deleted',
        'modified_by',
    ];

}
