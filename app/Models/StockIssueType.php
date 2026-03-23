<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIssueType extends Model
{
    use HasFactory;
    protected $fillable = [
        'issue_type_code',
        'issue_type_name_1',
        'issue_type_name_2',
        'is_discontinued',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
