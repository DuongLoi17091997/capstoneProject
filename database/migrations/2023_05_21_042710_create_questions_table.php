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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('titles');
            $table->string('question_type');
            $table->string('difficulty');
            $table->string('a_selection');
            $table->string('b_selection');
            $table->string('c_selection');
            $table->string('d_selection');
            $table->string('multiple_selection_result');
            $table->longText('writing_result');
            $table->string('status');
            $table->uuid('subject_id')->nullable(false);
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->timestamps();
        });
        Schema::table('examation_histories', function (Blueprint $table) {
            $table->uuid('exam_id')->nullable(false);
            $table->foreign('exam_id')->references('id')->on('examinations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
