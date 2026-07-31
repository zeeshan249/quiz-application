<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionAdvanced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets,SerializesModels;

    public function __construct(
        public int $quizSessionId,
        public int $questionId,
        public int $phaseEndsAt,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('quiz-session.'.$this->quizSessionId);
    }

    public function broadcastAs(): string
    {
        return 'QuestionAdvanced';
    }

    public function broadcastWith(): array
    {
        return [
            'questionId' => $this->questionId,
            'phaseEndsAt' => $this->phaseEndsAt,
        ];
    }
}
