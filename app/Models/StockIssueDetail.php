<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockIssueDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'stock_issue_id',
        'item_id',
        'unit_id',
        'batch_number',
        'quantity',
        'item_remark',
        'expire_date',
        'location_id',
        'issue_type',
        'is_updated',
        'is_deleted'
    ];
}
