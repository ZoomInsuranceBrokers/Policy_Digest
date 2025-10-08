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
        Schema::create('cd_master', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('comp_id');
            $table->string('insurance_name', 255);
            $table->string('cd_ac_name', 255);
            $table->string('cd_ac_no', 255);
            $table->decimal('minimum_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->string('cd_folder', 255)->nullable();
            $table->string('statment_file', 255)->nullable();
            $table->boolean('status')->default(1)->comment('1 = active, 0 = inactive');
            $table->timestamps();

            // If you have a companies table, you can add a foreign key:
            // $table->foreign('comp_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cd_master');
    }
};
