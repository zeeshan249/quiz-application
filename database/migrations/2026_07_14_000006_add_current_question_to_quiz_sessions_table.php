<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Added after `questions` exists to avoid a circular FK during table creation.
        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->foreignId('current_question_id')
                ->nullable()
                ->after('reveal_seconds')
                ->constrained('questions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_question_id');
        });
    }
};
