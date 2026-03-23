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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id('currency_id');
            $table->string('currency_code',20);
            $table->string('currency_name_1',20);
            $table->string('currency_name_2',20)->nullable();
            $table->decimal('exchange_rate');
            $table->boolean('is_base_currency')->nullable();
            $table->boolean('is_discontinued');
            $table->boolean('is_deleted');
            $table->integer('sort_id');
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
        Schema::dropIfExists('currencies');
    }
};
