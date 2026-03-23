<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemUOM extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'from_unit_id',
        'to_unit_id',
        'quantity',
        'points',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
