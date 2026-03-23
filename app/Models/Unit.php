<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = [
        'unit_name',
        'other_name',
        'is_discontinued',
        'is_deleted',
        'is_updated',
        'location_id',
        'modified_by'
    ];
}
