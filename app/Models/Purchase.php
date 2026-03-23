<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'purchase_voucher_number',
        'supplier_id',
        'purchase_date',
        'due_date',
        'remark',
        'total_amount',
        'total_item_discount',
        'is_delete',
        'delete_reason',
        'is_updated',
        'modified_by'
    ];
}
