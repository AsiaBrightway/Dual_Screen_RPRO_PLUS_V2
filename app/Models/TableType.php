<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableType extends Model
{
    use HasFactory;
    protected $fillable = [
        'table_type_code',
        'table_type_name_1',
        'table_type_name_2',
        'shape',
        'table_type_image',
        'is_room',
        'sort_id',
        'is_discontinued',
        'is_deleted',
        'is_updated',
        'location_id',
        'modified_by'
    ];
}
