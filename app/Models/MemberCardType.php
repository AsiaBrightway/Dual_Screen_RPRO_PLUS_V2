<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberCardType extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_card_type_name',
        'other_name',
        'discount_type',
        'amount_discount',
        'percent_discount',
        'remark',
        'location_id',
        'is_deleted',
        'is_updated',
        'is_discontinued',
        'modified_by'
    ];
}
