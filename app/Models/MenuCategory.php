<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'menu_category_name',
        'main_category_id',
        'menu_category_image',
        'store_location_id',
        'is_discontinued',
        'is_deleted',
        'modified_by'
    ];
}
