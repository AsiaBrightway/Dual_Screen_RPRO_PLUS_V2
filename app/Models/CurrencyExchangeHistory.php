<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyExchangeHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'history_date',
        'currency_id',
        'exchange_rate',
        'location_id',
        'is_updated',
        'modified_by',
    ];
}
