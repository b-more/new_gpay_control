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
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('payment_method_id');
            $table->string('account_number');
            $table->string('business_name');
            $table->string('payment_channel');
            $table->string('payment_reference_number');
            $table->string('txn_number')->nullable();
            $table->string('phone_number');
            $table->string('description')->nullable();
            $table->string('received_amount');
            $table->string('commission_charged');
            $table->string('payout_amount');
            $table->string('short_url')->nullable();
            $table->string('long_url')->nullable();
            $table->integer('status')->default("1");
            $table->integer('is_deleted')->default("0");
            $table->integer('is_refunded')->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
