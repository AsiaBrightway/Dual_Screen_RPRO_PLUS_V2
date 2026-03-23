<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();

        User::create([
            'name' => 'System Administrator',
            'username' => 'sysadmin',
            'user_role_id' => 1,
            'employee_id' => 0,
            'login_status' => true,
            'location_id' => 1,
            'is_discontinued' => false,
            'modified_by' => 1,
            'password' => Hash::make('admin123')
        ]);
    }
}
