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
        Schema::create('two_factors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(column: 'user_id');
            $table->string(column: 'otp')->nullable();
            $table->string('session_id')->nullable();
            $table->boolean(column: 'is_active')->default(0);
            $table->boolean(column: 'is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('two_factors');
    }
};
