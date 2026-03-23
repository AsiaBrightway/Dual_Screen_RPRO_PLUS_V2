<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Township extends Model
{
    use HasFactory;
    protected $fillable = [
        'township_name',
        'city_id',
        'is_discontinued',
        'is_updated',
    ];
}
