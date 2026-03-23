,191<?php

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
            Schema::create('stock_issue_types', function (Blueprint $table) {
                $table->id('issue_type_id');
                $table->string('issue_type_code', 191);
                $table->string('issue_type_name_1', 191);
                $table->string('issue_type_name_2', 191)->nullable();
                $table->boolean('is_discontinued')->nullable();
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
            Schema::dropIfExists('stock_issue_types');
        }
    };
