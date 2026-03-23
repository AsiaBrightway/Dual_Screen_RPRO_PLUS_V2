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
        Schema::create('modifiers', function (Blueprint $table) {
            $table->id('modifier_id');
            $table->string('modifier_code', 50);
            $table->string('modifier_name', 100);
            $table->integer('modifier_group_id')->nullable();
            $table->decimal('unit_cost', 20, 2);
            $table->decimal('unit_price', 20, 2);
            $table->integer('points');
            $table->boolean('is_price_to_main_item')->nullable();
            $table->string('remark', 191)->nulllable();
            $table->boolean('is_show_in_print')->nullable();
            $table->integer('sort_id');
            $table->boolean('is_discontinued')->nullable();
            $table->boolean('is_deleted')->nullable();
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
        Schema::dropIfExists('modifiers');
    }
};
