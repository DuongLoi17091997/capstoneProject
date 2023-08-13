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
            $table->id();
            $table->longText('titles');
            $table->string('type');
            $table->string('a_seletion');
            $table->string('b_seletion');
            $table->string('c_seletion');
            $table->string('d_seletion');
            $table->string('multiple_seletion_result');
            $table->longText('writing_result');
            $table->string('status');
            $table->foreignIdFor(Subjects::class);
            $table->timestamps();
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
