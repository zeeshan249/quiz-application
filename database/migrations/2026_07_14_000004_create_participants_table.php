<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_session_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            // Anonymous dedupe token (session cookie); no phone / PII
            $table->string('token', 64);
            $table->unsignedInteger('score')->default(0);
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            // One participant per browser per quiz (the counter source)
            $table->unique(['quiz_session_id', 'token']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
