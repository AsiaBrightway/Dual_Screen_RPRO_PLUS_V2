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
            Schema::create('stock_issue_details', function (Blueprint $table) {
                $table->id('stock_issue_detail_id');
                $table->integer('stock_issue_id');
                $table->integer('item_id');
                $table->integer('unit_id');
                $table->integer('batch_number');
                $table->decimal('quantity');
                $table->string('item_remark', 191)->nullable();
                $table->dateTime('expire_date')->nullable();
                $table->integer('location_id');
                $table->string('issue_type', 191)->nullable();
                $table->boolean('is_updated');
                $table->boolean('is_deleted')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('stock_issue_details');
        }
    };
