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
        Schema::create('session_token_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('token');
            $table->string('refresh_token');
            $table->string('token_expired');
            $table->string('refresh_token_expired');
            $table->uuid('user_Id')->nullable(false);
            $table->timestamps();
            $table->foreign('user_Id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_token_users');
    }
};
