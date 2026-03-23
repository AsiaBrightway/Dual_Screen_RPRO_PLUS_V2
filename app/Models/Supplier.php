<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_name',
        'other_name',
        'supplier_code',
        'phone_number',
        'email',
        'city_id',
        'township_id',
        'address',
        'remark',
        'is_discontinued',
        'is_deleted',
        'is_updated',
        'location_id',
        'modified_by',
    ];
}
