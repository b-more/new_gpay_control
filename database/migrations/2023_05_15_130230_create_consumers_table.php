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
        Schema::create('consumers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('nrc_front')->nullable();
            $table->string('nrc_back')->nullable();
            $table->string('selfie')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone_number')->unique()->nullable()->index();
            $table->string('avatar')->nullable();
            $table->string('password')->nullable();
            $table->string('otp');
            $table->string('gender')->nullable();
            $table->string('dob')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('nrc_number')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_deleted')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumers');
    }
};
