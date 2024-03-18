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
        Schema::create('auth_activity_trails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger(column: 'user_id');
            $table->string('session_id')->nullable();
            $table->string(column: 'ip_address')->nullable();
            $table->string(column: 'latitude')->nullable();
            $table->string(column: 'longitude')->nullable();
            $table->string(column: 'city')->nullable();
            $table->string(column: 'country')->nullable();
            $table->string(column: 'device_type')->nullable();
            $table->string(column:'browser')->nullable();
            $table->string(column: 'screen_time')->nullable();
            $table->string(column: 'activity_initiator')->nullable();//Account owner or system
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_activity_trails');
    }
};
