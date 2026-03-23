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
        Schema::create('customer_ledgers', function (Blueprint $table) {
            $table->id('customer_ledger_id');
            $table->dateTime('receive_date');
            $table->integer('sale_id')->nullable();
            $table->integer('table_id')->nullable();
            $table->integer('outstanding_batch_number');
            $table->integer('currency_id');
            $table->decimal('exchange_rate');
            $table->decimal('table_total_amount');
            $table->decimal('total_amount');
            $table->decimal('transport_charges');
            $table->decimal('service_charges');
            $table->decimal('tax');
            $table->decimal('total_discount');
            $table->decimal('member_card_discount');
            $table->decimal('coupon_discount');
            $table->decimal('receive_amount_from_prepaid_card');
            $table->decimal('recieve_amount');
            $table->string('transaction_type', 20);
            $table->string('remark', 191)->nullable();
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->string('is_deleted', 191)->nullable();
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_ledgers');
    }
};
