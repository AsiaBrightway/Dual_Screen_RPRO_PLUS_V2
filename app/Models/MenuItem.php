<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_category_id',
        'sub_category_id',
        'item_type_id',
        'item_code',
        'bar_code',
        'item_name',
        'other_name',
        'unit_id',
        'item_image',
        'location_id',
        'is_discontinued',
        'is_deleted',
        'modified_by',
    ];
}
