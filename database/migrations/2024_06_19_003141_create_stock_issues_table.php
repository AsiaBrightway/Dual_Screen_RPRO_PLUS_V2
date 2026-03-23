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
            Schema::create('stock_issues', function (Blueprint $table) {
                $table->id('stock_issue_id');
                $table->dateTime('issue_date');
                $table->string('issue_voucher_number', 191);
                $table->decimal('total_qty');
                $table->string('remark', 191);
                $table->integer('issue_type_id');
                $table->boolean('is_delete')->nullable();
                $table->string('delete_reason', 191)->nullable();
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
            Schema::dropIfExists('stock_issues');
        }
    };
