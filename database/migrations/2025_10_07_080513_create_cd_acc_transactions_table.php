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
        Schema::create('cd_acc_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cd_ac_id');
            $table->enum('credit_type', ['DEBIT', 'CREDIT']);
            $table->decimal('transaction_amount', 15, 2);
            $table->string('document')->nullable();
            $table->text('description')->nullable();
            $table->date('transaction_date');
            $table->timestamps();

            $table->foreign('cd_ac_id')->references('id')->on('cd_master')->onDelete('cascade');
            $table->index(['cd_ac_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cd_acc_transactions');
    }
};
