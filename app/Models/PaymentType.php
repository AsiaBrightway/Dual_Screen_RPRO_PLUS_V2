<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;
    protected $fillable = [
        'payment_type_name',
        'is_discontinued',
        'is_updated',
        'modified_by'
    ];
}
