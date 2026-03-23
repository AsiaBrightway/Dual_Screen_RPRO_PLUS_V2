<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsystemCheck extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'text_to_check',
        'location_id',
        'is_updated'
    ];
}
