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
        Schema::create('modifier_groups', function (Blueprint $table) {
            $table->id('modifier_group_id');
            $table->string('modifier_group_code',50);
            $table->string('modifier_group_name',50);
            $table->integer('location_id');
            $table->integer('sort_id');
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
        Schema::dropIfExists('modifier_groups');
    }
};
