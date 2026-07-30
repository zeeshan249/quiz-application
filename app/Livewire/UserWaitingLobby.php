<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\Participant;
use App\Models\QuizSession;
use Livewire\Attributes\On;

class UserWaitingLobby extends FrontendComponent
{
    public int $quizSessionId;

    public int $participantCount = 0;

    public function mount(): void
    {
        $this->quizSessionId = session('quiz_session_id');

        abort_if(! $this->quizSessionId, 404);

       $quizSession = QuizSession::findOrFail($this->quizSessionId);

    if ($quizSession->status === 'live') {
        $this->redirectRoute('frontend.quiz_live');
    }

        $this->participantCount = Participant::where(
            'quiz_session_id',
            $this->quizSessionId
        )->count();
    }

    #[On('participant-joined')]
    public function participantJoined(int $count, int $quizSessionId): void
    {

        $this->participantCount = $count;
    }

    #[On('echo:quiz.{quizSessionId},quiz.started')]
    public function quizStarted($event): void
    {
        // logger()->info('QuizStarted received', $event);

        // dd($event);
          $this->dispatch('quiz-started', [
        'started_at' => $event['started_at'],
        'message' => $event['message'],
    ]);
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
