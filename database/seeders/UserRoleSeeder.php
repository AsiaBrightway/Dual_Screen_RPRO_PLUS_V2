<?php

namespace Database\Seeders;

use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_roles')->truncate();

        UserRole::create([
            'user_role_name' => 'System_Administrator',
            'other_name' => 'sysadmin',
            'location_id' => 1,
            'is_discontinued' => false,
            'modified_by' => 1,
        ]);
        UserRole::create([
            'user_role_name' => 'Manager',
            'other_name' => 'Manager',
            'location_id' => 1,
            'is_discontinued' => false,
            'modified_by' => 1,
        ]);
        UserRole::create([
            'user_role_name' => 'Cashier',
            'other_name' => 'Cahsier',
            'location_id' => 1,
            'is_discontinued' => false,
            'modified_by' => 1,
        ]);
        UserRole::create([
            'user_role_name' => 'Waiter',
            'other_name' => 'Waiter',
            'location_id' => 1,
            'is_discontinued' => false,
            'modified_by' => 1,
        ]);
    }
}
