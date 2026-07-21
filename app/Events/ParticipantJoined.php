<?php

namespace App\Events;

use App\Models\Participant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParticipantJoined implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $quizSessionId
    ) {}

    public function broadcastOn(): array
    {
        logger()->info('broadcastOn()', [
            'channel' => "quiz.{$this->quizSessionId}",
        ]);

        return [
            new Channel("quiz.{$this->quizSessionId}"),
        ];
    }

  

    public function broadcastWith(): array
    {
        logger()->info('broadcastWith()', [
            'count' => Participant::where(
                'quiz_session_id',
                $this->quizSessionId
            )->count(),
        ]);

        return [
            'count' => Participant::where(
                'quiz_session_id',
                $this->quizSessionId
            )->count(),
        ];
    }
}
