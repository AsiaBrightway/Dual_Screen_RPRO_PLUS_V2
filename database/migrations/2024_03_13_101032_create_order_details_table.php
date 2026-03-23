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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id('order_detail_id');
            $table->integer('order_id');
            $table->integer('item_id');
            $table->integer('batch_number')->nullable();
            $table->decimal('promotion_price', 20, 2)->nullable();
            $table->integer('quantity');
            $table->string('remark', 100)->nullable();
            $table->boolean('is_ordered');
            $table->boolean('is_foc')->nullable();
            $table->string('order_type', 191)->nullable();
            $table->integer('ordered_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
