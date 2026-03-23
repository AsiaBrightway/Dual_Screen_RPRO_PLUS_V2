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
        Schema::create('member_card_types', function (Blueprint $table) {
            $table->id('member_card_type_id');
            $table->string('member_card_type_name', 100);
            $table->string('other_name', 100)->nullable();
            $table->string('discount_type', 191);
            $table->decimal('amount_discount', 20, 2);
            $table->decimal('percent_discount', 20, 2);
            $table->string('remark', 191)->nullable();
            $table->integer('location_id');
            $table->boolean('is_deleted');
            $table->boolean('is_updated');
            $table->boolean('is_discontinued');
            $table->integer('modified_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_card_types');
    }
};
