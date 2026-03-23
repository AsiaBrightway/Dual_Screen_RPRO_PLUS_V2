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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->integer('main_category_id');
            $table->integer('sub_category_id');
            $table->integer('item_type_id');
            $table->string('item_code', 100);
            $table->string('bar_code', 100);
            $table->string('item_name', 100);
            $table->string('other_name', 100)->nullable();
            $table->integer('unit_id');
            $table->string('item_image', 191)->nullable();
            $table->integer('location_id');
            $table->boolean('is_discontinued');
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
        Schema::dropIfExists('menu_items');
    }
};
