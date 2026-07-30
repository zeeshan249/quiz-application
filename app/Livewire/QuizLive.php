<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\QuizSession;
use Livewire\Attributes\On;

class QuizLive extends FrontendComponent
{
    #[On('countdown-finished')]
    public function countdownFinished(): void
    {
        $this->redirectRoute('frontend.live-quiz');
    }

    public function mount(): void
    {
        abort_unless(session()->has('quiz_session_id'), 403);

        $quiz = QuizSession::findOrFail(session('quiz_session_id'));

        abort_if($quiz->status !== 'live', 404);
    }

    public function render()
    {
        return $this->frontend(
            view('livewire.quiz-live')
        );
    }
}
