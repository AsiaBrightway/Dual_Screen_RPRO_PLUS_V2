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
        Schema::create('purchase_payment_logs', function (Blueprint $table) {
            $table->id('purchase_payment_log_id');
            $table->integer('purchase_id');
            $table->dateTime('paid_date');
            $table->string('voucher_discount', 191);
            $table->string('total_amount', 191);
            $table->string('tax', 191);
            $table->string('transport_charges', 191);
            $table->string('other_charges', 191);
            $table->string('paid_amount', 191);
            $table->string('net_amount', 191);
            $table->string('balance', 191);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_payment_logs');
    }
};
