<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasFactory;
    protected $fillable = [
        'modifier_code',
        'modifier_name',
        'modifier_group_id',
        'unit_cost',
        'unit_price',
        'points',
        'is_price_to_main_item',
        'remark',
        'is_show_in_print',
        'sort_id',
        'is_discontinued',
        'is_deleted',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
