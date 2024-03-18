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
        Schema::create('consumer_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('consumer_id')->nullable();
            $table->string('consumer_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('payment_reference_number');
            $table->string('channel')->nullable();
            $table->string('method')->nullable();
            $table->string('type')->nullable();
            $table->string('partner')->nullable();
            $table->string('partner_type')->nullable();
            $table->string('amount')->nullable();
            $table->string('bonus')->nullable();
            $table->string('purpose')->nullable();
            $table->text('custom_message')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('lead')->default(0);
            $table->longText('meta_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumer_transactions');
    }
};
