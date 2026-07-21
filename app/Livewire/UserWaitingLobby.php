<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\Participant;
use Livewire\Attributes\On;

class UserWaitingLobby extends FrontendComponent
{
    public int $quizSessionId;

    public int $participantCount = 0;

    public function mount(): void
    {
        $this->quizSessionId = session('quiz_session_id');

        abort_if(! $this->quizSessionId, 404);

        $this->participantCount = Participant::where(
            'quiz_session_id',
            $this->quizSessionId
        )->count();
    }

    #[On('echo:quiz.{quizSessionId},ParticipantJoined')]
    public function participantJoined($event)
    {
        logger()->info('Broadcast received', ['event' => $event]);
        $this->participantCount = $event['count'] ?? 0;
    }

    public function refreshCount()
    {
        $this->participantCount = Participant::where(
            'quiz_session_id',
            $this->quizSessionId
        )->count();
    }

    public function render()
    {
        return view('livewire.user-waiting-lobby')
            ->layout('components.layouts.frontend');
    }
}
