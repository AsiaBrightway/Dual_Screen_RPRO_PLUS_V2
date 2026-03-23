<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_name',
        'other_name',
        'floor_id',
        'is_open',
        'is_discontinued',
        'is_deleted',
        'is_updated',
        'location_id',
        'modified_by'
    ];
}
