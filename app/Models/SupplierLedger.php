<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_date',
        'supplier_id',
        'purchase_id',
        'purchase_return_id',
        'outstanding_batch_number',
        'currency_id',
        'exchange_rate',
        'total_amount',
        'transport_charges',
        'tax',
        'other_charges',
        'total_discount',
        'paid_amount',
        'return_amount',
        'transaction_type',
        'remark',
        'location_id',
        'is_updated',
        'is_deleted',
        'modified_by'
    ];
}
