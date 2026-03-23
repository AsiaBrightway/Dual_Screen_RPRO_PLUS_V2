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
        Schema::create('tables', function (Blueprint $table) {
            $table->id('table_id');
            $table->string('table_name', 191);
            $table->string('other_name', 191)->nullable();
            $table->integer('floor_id');
            $table->boolean('is_open')->nullable();
            $table->boolean('is_discontinued')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->boolean('is_updated');
            $table->integer('location_id');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
