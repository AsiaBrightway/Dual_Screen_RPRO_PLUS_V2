<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('payment_types')->truncate();

        PaymentType::create([
            'payment_type_name' => '---',
            'is_discontinued' => 0,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        PaymentType::create([
            'payment_type_name' => 'KBZPay',
            'is_discontinued' => 0,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        PaymentType::create([
            'payment_type_name' => 'AYAPay',
            'is_discontinued' => 0,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
        PaymentType::create([
            'payment_type_name' => 'CBPay',
            'is_discontinued' => 0,
            'is_updated' => 0,
            'modified_by' => 1,
        ]);
    }
}
