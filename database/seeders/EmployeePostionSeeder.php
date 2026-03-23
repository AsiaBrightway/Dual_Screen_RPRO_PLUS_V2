<?php

namespace Database\Seeders;

use App\Models\EmployeePosition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeePostionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_positions')->truncate();

        EmployeePosition::create([
            'position_name' => 'Cashier',
            'other_name' => 'Cashier',
            'is_discontinued' => false,
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => 1,
        ]);
        EmployeePosition::create([
            'position_name' => 'Waiter',
            'other_name' => 'Waiter',
            'is_discontinued' => false,
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => 1,
        ]);
    }
}
