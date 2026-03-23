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
        Schema::create('currency_exchange_histories', function (Blueprint $table) {
            $table->id('currency_exchange_history_id');
            $table->dateTime('history_date');
            $table->integer('currency_id');
            $table->decimal('exchange_rate');
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
        Schema::dropIfExists('currency_exchange_histories');
    }
};
