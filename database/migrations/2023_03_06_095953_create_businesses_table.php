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
        Schema::create('businesses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('district_id')->nullable();
            $table->unsignedBigInteger('country_id')->default(1);
            $table->unsignedBigInteger('business_category_id')->nullable();
            $table->unsignedBigInteger('business_type_id')->nullable();
            $table->unsignedBigInteger('collection_commission_id')->nullable();
            $table->unsignedBigInteger('disbursement_commission_id')->nullable();
            $table->string('account_number');
            $table->longText('certificate_of_incorporation')->nullable();
            $table->longText('tax_clearance')->nullable();
            $table->longText('director_nrc')->nullable();
            $table->longText('director_details')->nullable();
            $table->longText('pacra_printout')->nullable();
            $table->longText('supporting_documents')->nullable();
            $table->string('business_name');
            $table->string('business_email');
            $table->string('business_logo')->default("https://socialimpact.com/wp-content/uploads/2021/03/logo-placeholder.jpg");
            $table->string('business_address_line_1')->nullable();
            $table->string('business_phone_number')->nullable();
            $table->string('business_bank_account_number')->nullable();
            $table->string('business_bank_name')->nullable();
            $table->string('business_bank_account_name')->nullable();
            $table->string('business_bank_account_branch_name')->nullable();
            $table->string('business_bank_account_branch_code')->nullable();
            $table->string('business_bank_account_sort_code')->nullable();
            $table->string('business_bank_account_swift_code')->nullable();
            $table->string('business_tpin')->nullable();
            $table->string('business_reg_number')->nullable();
            $table->string('callback_url')->nullable();
            $table->string('payment_checkout')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
