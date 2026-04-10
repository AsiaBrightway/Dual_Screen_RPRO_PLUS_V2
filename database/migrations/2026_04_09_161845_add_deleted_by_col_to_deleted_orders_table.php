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
        Schema::table('deleted_orders', function (Blueprint $table) {
            $table->integer('deleted_by')->nullable()->after('ordered_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deleted_orders', function (Blueprint $table) {
            $table->dropColumn('deleted_by');
        });
    }
};
