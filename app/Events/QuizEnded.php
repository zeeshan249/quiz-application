<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizEnded implements ShouldBroadcastNow,SerializesModels
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(
        public int $quizSessionId,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('quiz-session.'.$this->quizSessionId);
    }

    public function broadcastAs(): string
    {
        return 'QuizEnded';
    }
}