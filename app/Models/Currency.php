<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;
    protected $fillable = [
        'currency_code',
        'currency_name_1',
        'currency_name_2',
        'exchange_rate',
        'is_base_currency',
        'is_discontinued',
        'is_deleted',
        'sort_id',
        'location_id',
        'is_updated',
        'modified_by',
    ];
}
