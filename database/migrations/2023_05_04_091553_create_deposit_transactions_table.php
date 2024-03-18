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
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('deposit_id');
            $table->unsignedBigInteger('business_id');
            $table->string('amount');
            $table->string('deposit_method'); //bank or mno
            $table->string('reference_number');
            $table->string('deposit_mode'); //automatic or manual
            $table->string('status');
            $table->string("deposit_slip")->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('recorded_by')->nullable();
            $table->unsignedBigInteger('authorised_by')->nullable();
            $table->string('old_balance');
            $table->string('new_balance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_transactions');
    }
};
