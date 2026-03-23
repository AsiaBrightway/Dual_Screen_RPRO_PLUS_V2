<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceive extends Model
{
    use HasFactory;
    protected $fillable = [
        'receive_voucher_number',
        'receive_date',
        'remark',
        'is_delete',
        'delete_reason',
        'is_updated',
        'modified_by'
    ];
}
