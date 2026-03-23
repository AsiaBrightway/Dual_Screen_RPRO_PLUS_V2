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
        Schema::create('sales', function (Blueprint $table) {
            $table->id('sale_id');
            $table->string('sale_voucher_number', 50);
            $table->integer('table_id');
            $table->integer('table_order_number');
            $table->integer('customer_id')->nullable();
            $table->integer('waiter_id');
            $table->integer('cashier_id');
            $table->dateTime('order_date');
            $table->integer('total_amount');
            $table->decimal('total_item_promo_amount', 20, 2);
            $table->integer('service_charges_amount')->nullable();
            $table->integer('service_charges_percent')->nullable();
            $table->integer('tax_amount')->nullable();
            $table->integer('tax_percent')->nullable();
            $table->integer('voucher_discount_amount')->nullable();
            $table->integer('voucher_discount_percent')->nullable();
            $table->string('member_card_code', 191)->nullable();
            $table->integer('member_card_amount')->nullable();
            $table->integer('member_card_percent')->nullable();
            $table->string('coupon_card_code', 191)->nullable();
            $table->integer('coupon_card_amount')->nullable();
            $table->integer('coupon_card_percent')->nullable();
            $table->integer('net_amount');
            $table->integer('payment_type_id');
            $table->string('online_paid', 191);
            $table->integer('paid_amount');
            $table->integer('balance_amount');
            $table->integer('change_amount');
            $table->integer('delivery_charges');
            $table->integer('is_delete')->nullable();
            $table->string('delete_reason', 191)->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
