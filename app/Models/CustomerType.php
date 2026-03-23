<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_type_name',
        'other_name',
        'customer_type_code',
        'is_discontinued',
        'is_updated',
        'modified_by'
    ];
}
