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
        Schema::create('stock_receive_details', function (Blueprint $table) {
            $table->id('stock_receive_detail_id');
            $table->integer('stock_receive_id');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->decimal('quantity');
            $table->decimal('unit_cost', 20, 2);
            $table->decimal('amount', 20, 2);
            $table->dateTime('expire_date')->nullable();
            $table->integer('batch_number');
            $table->boolean('is_updated');
            $table->boolean('is_deleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_receive_details');
    }
};
