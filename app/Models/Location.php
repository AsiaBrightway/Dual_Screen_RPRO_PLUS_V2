<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = [
        'location_code',
        'location_name',
        'location_address',
        'is_current_location',
        'is_discontinued',
        'is_updated'
    ];
}
