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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->string('purchase_voucher_number', 50);
            $table->integer('supplier_id');
            $table->dateTime('purchase_date');
            $table->dateTime('due_date');
            $table->string('remark', 191)->nullable();
            $table->decimal('total_amount', 20, 2);
            $table->decimal('total_item_discount', 20, 2);
            $table->boolean('is_delete')->nullable();
            $table->string('delete_reason', 150)->nullable();
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
        Schema::dropIfExists('purchases');
    }
};
