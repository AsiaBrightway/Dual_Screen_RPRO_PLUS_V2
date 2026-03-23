<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TablePrice extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_id',
        'quantity',
        'unit_price',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
