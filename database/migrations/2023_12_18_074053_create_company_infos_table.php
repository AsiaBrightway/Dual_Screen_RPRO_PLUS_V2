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
        Schema::create('company_infos', function (Blueprint $table) {
            $table->id('company_id');
            $table->string('company_name_1', 150);
            $table->string('company_name_2', 150)->nullable();
            $table->string('address_1', 150)->nullable();
            $table->string('address_2', 150)->nullable();
            $table->string('phone_number_1', 50)->nullable();
            $table->string('phone_number_2', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('website', 50)->nullable();
            $table->string('company_logo', 191)->nullable();
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
        Schema::dropIfExists('company_infos');
    }
};
