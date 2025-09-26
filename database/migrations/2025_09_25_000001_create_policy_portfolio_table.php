<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('policy_portfolio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('insured_name')->nullable();
            $table->string('product_name');
            $table->string('policy_number');
            $table->date('start_date');
            $table->date('expiry_date');
            $table->string('sum_insured');
            $table->string('pbst');
            $table->string('gross_premium');
            $table->string('insurance_company_name');
            $table->string('cash_deposit');
            $table->string('policy_copy')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('policy_portfolio');
    }
};
