<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckDevice extends Model
{
    use HasFactory;
    protected $fillable = [
        'mac_address'
    ];
}
