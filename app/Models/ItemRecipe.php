<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRecipe extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'unit_id',
        'recipe_item_id',
        'recipe_item_unit_id',
        'quantity',
        'location_id',
        'is_updated',
        'is_deleted',
        'modified_by'
    ];
}
