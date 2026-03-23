<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'purcahse_return_id',
        'item_id',
        'unit_id',
        'batch_number',
        'quantity',
        'unit_cost',
        'discount_amount',
        'expire_date',
        'is_foc',
        'remark',
        'location_id',
        'is_updated',
        'is_deleted'
    ];
}
