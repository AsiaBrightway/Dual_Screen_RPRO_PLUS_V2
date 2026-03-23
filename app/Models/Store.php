<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'store_code',
        'store_name_1',
        'store_name_2',
        'sort_id',
        'is_discontinued',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
