<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Subjects;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('type');
            $table->string('description');
            $table->integer('limitedTime');
            $table->integer('numberOfQuestions');
            $table->string('difficulty');
            $table->boolean('status')->default(true);
            $table->uuid('subject_id')->nullable(false);
            $table->string('author');
            $table->string('thumbnail');
            $table->timestamps();
            $table->foreign('subject_id')->references('id')->on('subjects');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examinations');
    }
};
