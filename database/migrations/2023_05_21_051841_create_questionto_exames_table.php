<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Questions;
use App\Models\Examination;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questionto_exames', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id')->nullable(false);
            $table->foreign('exam_id')->references('id')->on('examinations');
            $table->uuid('question_id')->nullable(false);
            $table->foreign('question_id')->references('id')->on('questions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionto_exames');
    }
};
