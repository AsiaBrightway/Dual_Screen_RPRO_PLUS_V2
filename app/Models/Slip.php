<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{
    use HasFactory;
    protected $fillable = [
        'printer_name',
        'slip_number',
        'slip_date',
        'is_updated'
    ];
}
