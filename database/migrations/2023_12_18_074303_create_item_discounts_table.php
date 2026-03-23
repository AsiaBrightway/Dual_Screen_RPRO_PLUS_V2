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
        Schema::create('item_discounts', function (Blueprint $table) {
            $table->id('item_discount_id');
            $table->string('item_id', 191);
            $table->string('description', 191);
            $table->string('other_description', 191)->nullable();
            $table->string('item_price', 191);
            $table->string('buy_quantity', 191);
            $table->string('discount_type', 191);
            $table->decimal('amount_discount', 20, 2);
            $table->decimal('percent_discount', 20, 2);
            $table->decimal('promotion_price', 20, 2);

            $table->boolean('monday')->nullable();
            $table->boolean('tuesday')->nullable();
            $table->boolean('wednesday')->nullable();
            $table->boolean('thursday')->nullable();
            $table->boolean('friday')->nullable();
            $table->boolean('saturday')->nullable();
            $table->boolean('sunday')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->time('start_happy_hour')->nullable();
            $table->time('end_happy_hour')->nullable();

            $table->integer('location_id');
            $table->boolean('is_updated')->nullable();
            $table->boolean('is_deleted');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_discounts');
    }
};
