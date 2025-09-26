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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('policy_portfolio_id')->nullable();
            $table->boolean('is_marine')->default(0); // 0 = Regular, 1 = Marine

            // Common fields for both regular and marine
            $table->string('policy_number')->nullable();
            $table->string('policy_period')->nullable();
            $table->decimal('estimated_loss_amount', 15, 2)->nullable();
            $table->date('date_of_loss')->nullable();

            // Regular claims fields
            $table->string('insured_name')->nullable();
            $table->string('policy_type')->nullable();
            $table->text('brief_description_of_loss')->nullable();
            $table->text('details_of_affected_items')->nullable();
            $table->text('complete_loss_location')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->string('contact_person_email')->nullable();

            // Marine claims specific fields
            $table->string('name_of_insured')->nullable();
            $table->text('consignor_name_address')->nullable();
            $table->text('consignee_name_address')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->decimal('invoice_value', 15, 2)->nullable();
            $table->string('lr_gr_airway_bl_no')->nullable();
            $table->date('lr_gr_airway_bl_date')->nullable();
            $table->string('transporter_name')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('vehicle_container_no')->nullable();
            $table->date('consignment_received_date')->nullable();
            $table->string('place_of_loss')->nullable();
            $table->string('nature_of_loss')->nullable();
            $table->text('survey_address')->nullable();
            $table->string('spoc_name')->nullable();
            $table->string('spoc_phone')->nullable();
            $table->text('item_commodity_description')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('company_id')->references('id')->on('company_master')->onDelete('cascade');
            $table->foreign('policy_portfolio_id')->references('id')->on('policy_portfolio')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
