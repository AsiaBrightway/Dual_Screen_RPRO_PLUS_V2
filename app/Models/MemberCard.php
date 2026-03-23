<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'member_card_type_id',
        'member_card_code',
        'create_date',
        'expire_date',
        'has_expire',
        'remark',
        'is_expired',
        'is_discontinued',
        'is_deleted',
        'location_id',
        'is_updated',
        'modified_by'
    ];
}
