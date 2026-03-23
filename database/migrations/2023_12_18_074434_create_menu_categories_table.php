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
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id('category_id');
            $table->string('menu_category_name', 100);
            $table->integer('main_category_id');
            $table->string('menu_category_image', 191)->nullable();
            $table->integer('store_location_id');
            $table->boolean('is_discontinued');
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
        Schema::dropIfExists('menu_categories');
    }
};
