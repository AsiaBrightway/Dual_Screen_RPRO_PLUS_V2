<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRolePermission extends Model
{
    use HasFactory;
    protected $fillable = [
        'role_id',
        'form_menu_id',
        'is_updated',
        'is_deleted'
    ];
}
