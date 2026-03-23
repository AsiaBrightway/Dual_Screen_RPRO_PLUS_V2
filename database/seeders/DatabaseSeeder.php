<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\CustomerType;
use App\Models\Employee;
use App\Models\User;
use App\Models\Floor;
use App\Models\FormMenu;
use App\Models\ItemType;
use App\Models\UserRole;
use App\Models\PaymentType;
use App\Models\MainCategory;
use Illuminate\Database\Seeder;
use App\Models\EmployeePosition;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            UserRoleSeeder::class,
            ItemTypeSeeder::class,
            PaymentTypeSeeder::class,

            // EmployeePostionSeeder::class,
            // FormMenuSeeder::class,            
            // MainCategorySeeder::class,
          
        ]);
    }
}
