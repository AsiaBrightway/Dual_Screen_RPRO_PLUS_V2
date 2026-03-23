<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierGroup extends Model
{
    use HasFactory;
    protected $fillable = [
        'modifier_group_code',
        'modifier_group_name',
        'location_id',
        'sort_id',
        'is_discontinued',
        'is_deleted',
        'modified_by'
    ];
}
