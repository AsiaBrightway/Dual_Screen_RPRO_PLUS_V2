<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'other_name',
        'employee_code',
        'employee_position_id',
        'is_terminate',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
