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
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->string('company_name', 50);
            $table->string('phone_number', 50);
            $table->integer('city_id');
            $table->integer('township_id');
            $table->string('address', 100);
            $table->string('remark', 191)->nullable();
            $table->boolean('is_discontinued')->nullable();
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
