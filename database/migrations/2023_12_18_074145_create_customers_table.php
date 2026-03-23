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
        Schema::create('customers', function (Blueprint $table) {
            $table->id('customer_id');
            $table->string('customer_name', 50);
            $table->string('other_name', 50)->nullable();
            $table->string('customer_code', 50)->nullable();
            $table->integer('customer_type_id');
            $table->string('gender', 50);
            $table->dateTime('date_of_birth')->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->integer('city_id');
            $table->integer('township_id');
            $table->string('address', 191)->nullable();
            $table->string('remark', 191)->nullable();
            $table->integer('location_id');
            $table->boolean('is_discontinued')->nullable();
            $table->boolean('is_deleted')->nullable();
            $table->boolean('is_updated')->nullable();
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
