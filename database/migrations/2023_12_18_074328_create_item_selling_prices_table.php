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
        Schema::create('item_selling_prices', function (Blueprint $table) {
            $table->id('item_selling_price_id');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->integer('currency_id');
            $table->decimal('unit_cost', 20, 2);
            $table->string('item_selling_price', 191);
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
        Schema::dropIfExists('item_selling_prices');
    }
};
