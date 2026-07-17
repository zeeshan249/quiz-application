<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            // null = timed out / no selection
            $table->foreignId('question_option_id')->nullable()->constrained()->nullOnDelete();
            // Whether THIS participant got it right (computed server-side on submit)
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('response_ms')->nullable();
            $table->unsignedInteger('points_awarded')->default(0);
            $table->timestamps();

            // One answer per question per participant
            $table->unique(['participant_id', 'question_id']);
            // Powers the reveal bar: count correct vs wrong per question
            $table->index(['question_id', 'is_correct']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
