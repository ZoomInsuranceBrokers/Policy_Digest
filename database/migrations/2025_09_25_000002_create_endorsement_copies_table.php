<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('endorsement_copies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_portfolio_id');
            $table->string('document');
            $table->string('remark')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('endorsement_copies');
    }
};
