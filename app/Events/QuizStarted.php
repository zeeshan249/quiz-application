<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizStarted implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $quizSessionId,
        public int $startedAt,
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new Channel("quiz.{$this->quizSessionId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'quiz.started';
    }

    public function broadcastWith(): array
    {
        return [
            'quizSessionId' => $this->quizSessionId,
            'started_at' => $this->startedAt,
            'message' => 'The first question will appear in 20 seconds.',
        ];
    }
}