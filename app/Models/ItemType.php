<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemType extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_type_name',
        'other_name',
        'is_discontinued',
        'is_deleted',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
