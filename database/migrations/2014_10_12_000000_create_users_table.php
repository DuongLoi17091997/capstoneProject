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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_Name');
            $table->string('last_Name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status')->nullable()->default(true);
            $table->string('type')->default('normal');
            $table->string('phone')->nullable()->unique();
            $table->string('address')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();
            $table->string('hobby')->nullable();
            $table->string('avatar')->nullable();
            $table->date('birthdate')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
