<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReprintVoucher extends Model
{
    use HasFactory;
    protected $fillable = [
        'sales_id',
        'printed_by',
        'printed_date',
        'location_id',
        'is_updated'
    ];
}
