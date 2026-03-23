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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id('coupon_id');
            $table->string('coupon_code', 50);
            $table->string('coupon_name', 50);
            $table->string('discount_type', 50);
            $table->decimal('amount_discount');
            $table->decimal('percent_discount', 20, 2);
            $table->decimal('min_order_amount', 20, 2)->nullable();
            $table->dateTime('expire_date')->nullable();
            $table->boolean('is_used');
            $table->boolean('is_discontinued');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('coupons');
    }
};
