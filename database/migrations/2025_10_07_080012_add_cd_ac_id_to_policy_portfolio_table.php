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
        Schema::table('policy_portfolio', function (Blueprint $table) {
            $table->unsignedBigInteger('cd_ac_id')->nullable()->after('id');
            $table->foreign('cd_ac_id')->references('id')->on('cd_master')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('policy_portfolio', function (Blueprint $table) {
            $table->dropForeign(['cd_ac_id']);
            $table->dropColumn('cd_ac_id');
        });
    }
};
