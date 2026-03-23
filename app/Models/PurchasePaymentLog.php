<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePaymentLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_id',
        'paid_date',
        'voucher_discount',
        'total_amount',
        'tax',
        'transport_charges',
        'other_charges',
        'paid_amount',
        'net_amount',
        'balance'
    ];
}
