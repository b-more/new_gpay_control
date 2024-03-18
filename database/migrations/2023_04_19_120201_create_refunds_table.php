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
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('account_number');
            $table->string('business_name');
            $table->string('payment_channel');
            $table->string('payment_reference_number');
            $table->string('refund_reference_number');
            $table->string('phone_number');
            $table->string('reason');
            $table->string('refunded_amount');
            $table->string('commission_charged');
            $table->string('total_amount');
            $table->integer('status')->default("2"); //success
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
