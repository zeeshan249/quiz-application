<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('join_code');
            $table->enum('status', ['draft', 'lobby', 'live', 'ended'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Synchronized-timeline config
            $table->unsignedSmallInteger('answer_seconds')->default(20);
            $table->unsignedSmallInteger('reveal_seconds')->default(6);

            // Synchronized-timeline state (current_question_id added in a later migration
            // to avoid a circular FK with the questions table)
            $table->enum('phase', ['question', 'reveal'])->nullable();
            $table->timestamp('phase_ends_at')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            // Fast lookup of the active session for a given code
            $table->index(['join_code', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_sessions');
    }
};
