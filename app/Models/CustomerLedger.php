<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model
{
    use HasFactory;
    protected $fillable = [
        'receive_date',
        'sale_id',
        'table_id',
        'outstanding_batch_number',
        'currency_id',
        'exchange_rate',
        'table_total_amount',
        'total_amount',
        'transport_charges',
        'service_charges',
        'tax',
        'total_discount',
        'member_card_discount',
        'coupon_discount',
        'receive_amount_from_prepaid_card',
        'recieve_amount',
        'transaction_type',
        'remark',
        'location_id',
        'is_updated',
        'is_deleted',
        'modified_by'
    ];
}
