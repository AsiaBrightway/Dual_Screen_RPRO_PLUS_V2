<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormMenu extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_description',
        'form_name',
        'parent_form_menu_id',
        'is_active',
        'is_updated',
        'modified_by'
    ];
}
