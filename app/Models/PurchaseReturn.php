<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_return_date',
        'purchase_return_voucher_number',
        'purchase_id',
        'supplier_id',
        'store_id',
        'currency_id',
        'exchange_rate',
        'total_amount',
        'transport_charges',
        'total_discount',
        'remark',
        'is_cancel',
        'cancel_by',
        'cancel_date',
        'cancel_reason',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
