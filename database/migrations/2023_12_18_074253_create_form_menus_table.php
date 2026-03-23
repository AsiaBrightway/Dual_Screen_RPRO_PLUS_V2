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
        Schema::create('form_menus', function (Blueprint $table) {
            $table->id('form_menu_id');
            $table->string('form_description',100)->nullable();
            $table->string('form_name',100);
            $table->integer('parent_form_menu_id')->nullable();
            $table->boolean('is_active');
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
        Schema::dropIfExists('form_menus');
    }
};
