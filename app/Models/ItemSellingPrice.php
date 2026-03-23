<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemSellingPrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'unit_id',
        'currency_id',
        'unit_cost',
        'item_selling_price',
        'is_updated',
        'modified_by'
    ];
}
