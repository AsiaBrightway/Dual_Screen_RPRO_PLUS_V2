<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePosition extends Model
{
    use HasFactory;
    protected $fillable = [
        'position_name',
        'other_name',
        'is_discontinued',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
