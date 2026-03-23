<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name_1',
        'company_name_2',
        'address_1',
        'address_2',
        'phone_number_1',
        'phone_number_2',
        'email',
        'website',
        'company_logo',
        'location_id',
        'is_updated'
    ];
}
