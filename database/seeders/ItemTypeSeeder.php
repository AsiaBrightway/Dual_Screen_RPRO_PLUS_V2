<?php

namespace Database\Seeders;

use App\Models\ItemType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('item_types')->truncate();

        ItemType::create([
            'item_type_name' => 'Menu Item',
            'other_name' => 'Menu Item',
            'is_discontinued' => false,
            'is_deleted' => false,
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => 1,
        ]);
        ItemType::create([
            'item_type_name' => 'Stock Item',
            'other_name' => 'Stock Item',
            'is_discontinued' => false,
            'is_deleted' => false,
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => 1,
        ]);
        ItemType::create([
            'item_type_name' => 'Menu & Stock',
            'other_name' => 'Menu & Stock',
            'is_discontinued' => false,
            'is_deleted' => false,
            'location_id' => 1,
            'is_updated' => false,
            'modified_by' => 1,
        ]);
    }
}
