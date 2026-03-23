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
        Schema::create('item_uoms', function (Blueprint $table) {
            $table->id('item_uom_id');
            $table->integer('item_id');
            $table->integer('from_unit_id');
            $table->integer('to_unit_id');
            $table->decimal('quantity');
            $table->integer('points');
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
        Schema::dropIfExists('item_uoms');
    }
};
