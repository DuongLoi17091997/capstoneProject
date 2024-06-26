<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Examination;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('examation_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('score')->nullable();
            $table->string('result')->nullable();
            $table->longText('comments')->nullable();
            $table->integer('time_for_completed')->nullable();
            $table->boolean('status')->default(true);
            $table->uuid('user_id')->nullable(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examation_histories');
    }
};
