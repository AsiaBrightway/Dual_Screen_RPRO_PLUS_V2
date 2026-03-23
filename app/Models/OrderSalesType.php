<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSalesType extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_sales_type_code',
        'order_sales_type_name_1',
        'order_sales_type_name_2',
        'is_discontinued',
        'location_id',
        'is_updated'
    ];
}
