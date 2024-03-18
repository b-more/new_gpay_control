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
        Schema::create('payout_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('payout_id');
            $table->string('amount');
            $table->string('transaction_charge');
            $table->string('amount_payable');
            $table->string('internal_reference_number');
            $table->string('bank_reference_number')->nullable();
            $table->string('status');
            $table->string('transaction_method')->default('auto');
            $table->longText('remarks')->nullable();
            $table->unsignedBigInteger('initiated_by');
            $table->unsignedBigInteger('authorised_by')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->string('old_balance')->nullable();
            $table->string('new_balance')->nullable();
            $table->string('business_bank_account_number');
            $table->string('business_bank_account_name');
            $table->string('business_bank_account_branch_name')->nullable();
            $table->string('business_bank_account_branch_code')->nullable();
            $table->string('business_bank_account_sort_code')->nullable();
            $table->string('business_bank_account_swift_code')->nullable();
            $table->string('initiated_at');
            $table->string('authorised_at')->nullable();
            $table->string('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_transactions');
    }
};
