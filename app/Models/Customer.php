<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name',
        'other_name',
        'customer_code',
        'customer_type_id',
        'gender',
        'date_of_birth',
        'phone_number',
        'email',
        'city_id',
        'township_id',
        'address',
        'remark',
        'location_id',
        'is_discontinued',
        'is_deleted',
        'is_updated',
        'modified_by'
    ];
}
