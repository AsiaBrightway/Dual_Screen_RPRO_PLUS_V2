<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'main_category_name',
        'store_location_id',
        'is_discontinued',
        'is_deleted',
        'modified_by'
    ];
}
