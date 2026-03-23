<?php

namespace Database\Seeders;

use App\Models\FormMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('form_menus')->truncate();

        FormMenu::create([
            'form_name' => 'Dashboard',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Store',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Dine-In',
            'parent_form_menu_id' => 2,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Sale-Lists',
            'parent_form_menu_id' => 2,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Reservation',
            'parent_form_menu_id' => 2,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Customers',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Customer',
            'parent_form_menu_id' => 6,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Customer Type',
            'parent_form_menu_id' => 6,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock Control',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock Receive',
            'parent_form_menu_id' => 9,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Receive',
            'parent_form_menu_id' => 10,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Receive Lists',
            'parent_form_menu_id' => 10,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock Issue',
            'parent_form_menu_id' => 9,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Issue',
            'parent_form_menu_id' => 13,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Issue Lists',
            'parent_form_menu_id' => 13,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Issue Type',
            'parent_form_menu_id' => 9,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock Purchase',
            'parent_form_menu_id' => 9,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Purchase',
            'parent_form_menu_id' => 17,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Purchase Lists',
            'parent_form_menu_id' => 17,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Card',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Coupon Card',
            'parent_form_menu_id' => 20,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Member Card',
            'parent_form_menu_id' => 20,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Card',
            'parent_form_menu_id' => 22,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Card Type',
            'parent_form_menu_id' => 22,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Users',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Employee',
            'parent_form_menu_id' => 25,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Employee',
            'parent_form_menu_id' => 26,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Employee Position',
            'parent_form_menu_id' => 26,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Users',
            'parent_form_menu_id' => 25,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'User',
            'parent_form_menu_id' => 29,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'User Role',
            'parent_form_menu_id' => 29,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Suppliers',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Supplier',
            'parent_form_menu_id' => 32,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Supplier Lists',
            'parent_form_menu_id' => 32,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Configuration',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Item',
            'parent_form_menu_id' => 35,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Category',
            'parent_form_menu_id' => 36,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Item',
            'parent_form_menu_id' => 36,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Unit',
            'parent_form_menu_id' => 36,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Discount',
            'parent_form_menu_id' => 36,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Price Control',
            'parent_form_menu_id' => 36,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Floor',
            'parent_form_menu_id' => 35,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Table',
            'parent_form_menu_id' => 35,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Location',
            'parent_form_menu_id' => 35,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Delivery',
            'parent_form_menu_id' => 35,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Reports',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock-In',
            'parent_form_menu_id' => 45,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Stock-Out',
            'parent_form_menu_id' => 45,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Purchase',
            'parent_form_menu_id' => 45,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Sales',
            'parent_form_menu_id' => 45,
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        FormMenu::create([
            'form_name' => 'Setting',
            'is_active' => 1,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
    }
}
