<?php

namespace Database\Seeders;

use App\Models\MainCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('main_categories')->truncate();

        MainCategory::create([
            'main_category_name' => 'Bar',
            'store_location_id' => 1,
            'is_discontinued' => false,
            'is_deleted' => false,
            'modified_by' => 1,
        ]);
        MainCategory::create([
            'main_category_name' => 'Kitchen',
            'store_location_id' => 1,
            'is_discontinued' => false,
            'is_deleted' => false,
            'modified_by' => 1,
        ]);
        MainCategory::create([
            'main_category_name' => 'Refrigerator',
            'store_location_id' => 1,
            'is_discontinued' => false,
            'is_deleted' => false,
            'modified_by' => 1,
        ]);
        MainCategory::create([
            'main_category_name' => 'Service',
            'store_location_id' => 1,
            'is_discontinued' => false,
            'is_deleted' => false,
            'modified_by' => 1,
        ]);
    }
}
