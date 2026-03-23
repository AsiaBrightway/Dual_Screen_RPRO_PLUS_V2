<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIssue extends Model
{
    use HasFactory;
    protected $fillable = [
        'issue_date',
        'issue_voucher_number',
        'total_qty',
        'remark',
        'issue_type_id',
        'is_delete',
        'delete_reason',
        'is_updated',
        'modified_by'
    ];
}
