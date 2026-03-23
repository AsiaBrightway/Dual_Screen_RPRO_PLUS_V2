<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
    protected $fillable = [
        'floor_name',
        'other_name',
        'floor_code',
        'is_discontinued',
        'is_deleted',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
