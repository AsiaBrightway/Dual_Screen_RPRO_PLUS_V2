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
        Schema::create('member_cards', function (Blueprint $table) {
            $table->id('member_card_id');
            $table->integer('customer_id');
            $table->integer('member_card_type_id');
            $table->string('member_card_code', 50);
            $table->dateTime('create_date');
            $table->dateTime('expire_date')->nullable();
            $table->boolean('has_expire')->nullable();
            $table->string('remark', 191)->nullable();
            $table->boolean('is_expired')->nullable();
            $table->boolean('is_discontinued');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('member_cards');
    }
};
