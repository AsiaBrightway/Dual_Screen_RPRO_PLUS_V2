<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_role_name',
        'other_name',
        'location_id',
        'is_discontinued',
        'modified_by'
    ];
}
