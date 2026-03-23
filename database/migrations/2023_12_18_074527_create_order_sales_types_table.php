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
        Schema::create('order_sales_types', function (Blueprint $table) {
            $table->id('order_sales_type_id');
            $table->string('order_sales_type_code',50);
            $table->string('order_sales_type_name_1',50);
            $table->string('order_sales_type_name_2',50)->nullable();
            $table->boolean('is_discontinued')->nullable();
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sales_types');
    }
};
