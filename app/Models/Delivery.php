<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'phone_number',
        'city_id',
        'township_id',
        'address',
        'remark',
        'is_discontinued',
        'modified_by'
    ];
}
