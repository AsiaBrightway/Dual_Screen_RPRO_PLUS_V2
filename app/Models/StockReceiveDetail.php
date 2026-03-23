<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockReceiveDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_receive_id',
        'item_id',
        'unit_id',
        'quantity',
        'unit_cost',
        'amount',
        'expire_date',
        'batch_number',
        'is_updated',
        'is_deleted'
    ];
}
