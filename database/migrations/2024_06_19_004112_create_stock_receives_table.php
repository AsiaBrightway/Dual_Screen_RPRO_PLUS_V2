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
            Schema::create('stock_receives', function (Blueprint $table) {
                $table->id('stock_receive_id');
                $table->string('receive_voucher_number', 50);
                $table->dateTime('receive_date');
                $table->string('remark', 191)->nullable();
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
            Schema::dropIfExists('stock_receives');
        }
    };
