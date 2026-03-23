<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
       'table_id',
       'table_order_number',
       'name',
       'phone_number',
       'number_of_person',
       'reservation_date',
       'reservation_time',
       'modified_by'
    ];
}
