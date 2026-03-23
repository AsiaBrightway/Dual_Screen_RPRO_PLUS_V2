<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = [
        'sale_voucher_number',
        'table_id',
        'table_order_number',
        'customer_id',
        'waiter_id',
        'cashier_id',
        'order_date',
        'total_amount',
        'total_item_promo_amount',
        'service_charges_amount',
        'service_charges_percent',
        'tax_amount',
        'tax_percent',
        'voucher_discount_amount',
        'voucher_discount_percent',
        'member_card_code',
        'member_card_amount',
        'member_card_percent',
        'coupon_card_code',
        'coupon_card_amount',
        'coupon_card_percent',
        'net_amount',
        'payment_type_id',
        'online_paid',
        'paid_amount',
        'balance_amount',
        'change_amount',
        'delivery_charges',
        'voucher_foc',
        'is_delete',
        'delete_reason',
        'deleted_by'
    ];
}
