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
        Schema::create('purchase_returns', function (Blueprint $table) {
            $table->id('purchase_return_id');
            $table->dateTime('purchase_return_date');
            $table->string('purchase_return_voucher_number', 50);
            $table->integer('purchase_id')->nullable();
            $table->integer('supplier_id');
            $table->integer('store_id');
            $table->integer('currency_id');
            $table->decimal('exchange_rate');
            $table->decimal('total_amount', 20, 2);
            $table->decimal('transport_charges', 20, 2);
            $table->decimal('total_discount', 20, 2);
            $table->string('remark', 191)->nullable();
            $table->boolean('is_cancel')->nullable();
            $table->integer('cancel_by')->nullable();
            $table->dateTime('cancel_date')->nullable();
            $table->string('cancel_reason', 150)->nullable();
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_returns');
    }
};
