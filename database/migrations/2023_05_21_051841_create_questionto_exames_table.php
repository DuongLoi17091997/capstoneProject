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
            $table->id();
            $table->foreignIdFor(Examination::class);
            $table->foreignIdFor(Questions::class);
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
