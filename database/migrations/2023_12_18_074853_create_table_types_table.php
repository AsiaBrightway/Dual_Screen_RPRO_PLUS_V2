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
        Schema::create('table_types', function (Blueprint $table) {
            $table->id('table_type_id');
            $table->string('table_type_code',50);
            $table->string('table_type_name_1',100);
            $table->string('table_type_name_2',100)->nullable();
            $table->string('shape',50)->nullable();
            $table->string('table_type_image',100)->nullable();
            $table->boolean('is_room')->nullable();
            $table->integer('sort_id');
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
        Schema::dropIfExists('table_types');
    }
};
