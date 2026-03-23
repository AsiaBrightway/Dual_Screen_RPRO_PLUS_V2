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
        Schema::create('purchase_return_details', function (Blueprint $table) {
            $table->id('purcahse_return_detail_id');
            $table->integer('purcahse_return_id');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->integer('batch_number')->nullable();
            $table->decimal('quantity');
            $table->decimal('unit_cost');
            $table->decimal('discount_amount');
            $table->dateTime('expire_date')->nullable();
            $table->boolean('is_foc')->nullable();
            $table->string('remark',150)->nullable();
            $table->integer('location_id');
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
        Schema::dropIfExists('purchase_return_details');
    }
};
