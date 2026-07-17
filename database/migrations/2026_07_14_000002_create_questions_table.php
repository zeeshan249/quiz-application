<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_session_id')->constrained()->cascadeOnDelete();
            $table->text('text');
            $table->unsignedInteger('position')->default(0);
            $table->unsignedInteger('points')->default(1);
            // Per-question override; falls back to the session answer_seconds when null
            $table->unsignedSmallInteger('time_limit')->nullable();
            $table->timestamps();

            // Ordered walk through a quiz's questions
            $table->index(['quiz_session_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
