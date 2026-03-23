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
        Schema::create('item_recipes', function (Blueprint $table) {
            $table->id('item_recipe_id');
            $table->integer('item_id');
            $table->integer('unit_id');
            $table->integer('recipe_item_id');
            $table->integer('recipe_item_unit_id');
            $table->integer('quantity');
            $table->integer('location_id');
            $table->boolean('is_updated');
            $table->boolean('is_deleted')->nullable();
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_recipes');
    }
};
