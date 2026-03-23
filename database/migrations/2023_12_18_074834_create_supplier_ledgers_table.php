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
        Schema::create('supplier_ledgers', function (Blueprint $table) {
            $table->id('supplier_ledger_id');
            $table->dateTime('payment_date');
            $table->integer('supplier_id');
            $table->integer('purchase_id')->nullable();
            $table->integer('purchase_return_id')->nullable();
            $table->integer('outstanding_batch_number');
            $table->integer('currency_id');
            $table->decimal('exchange_rate');
            $table->decimal('total_amount');
            $table->decimal('transport_charges');
            $table->decimal('tax');
            $table->decimal('other_charges');
            $table->decimal('total_discount');
            $table->decimal('paid_amount');
            $table->decimal('return_amount');
            $table->string('transaction_type', 50);
            $table->string('remark', 191)->nullable();
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->boolean('is_deleted')->nullable();
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_ledgers');
    }
};
