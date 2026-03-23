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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id('purchase_detail_id');
            $table->integer('purchase_id');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->integer('batch_number');
            $table->decimal('quantity');
            $table->decimal('unit_cost', 20, 2);
            $table->decimal('discount_amount', 20, 2);
            $table->dateTime('expire_date')->nullable();
            $table->boolean('is_foc')->nullable();
            $table->boolean('is_updated')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
