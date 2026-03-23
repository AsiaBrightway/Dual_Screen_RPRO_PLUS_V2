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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('supplier_name', 100);
            $table->string('other_name', 100)->nullable();
            $table->string('supplier_code', 50);
            $table->string('phone_number', 50);
            $table->string('email', 50)->nullable();
            $table->integer('city_id');
            $table->integer('township_id');
            $table->string('address', 191);
            $table->string('remark', 191)->nullable();
            $table->boolean('is_discontinued');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('suppliers');
    }
};
