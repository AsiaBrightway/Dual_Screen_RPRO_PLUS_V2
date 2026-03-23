<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reprint_vouchers', function (Blueprint $table) {
            $table->id('reprint_voucher_id');
            $table->integer('sales_id');
            $table->string('printed_by', 191);
            $table->dateTime('printed_date');
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reprint_vouchers');
    }
};
