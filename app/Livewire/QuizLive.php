<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\Answer;
use App\Models\Participant;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuizSession;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;

class QuizLive extends FrontendComponent
{
    public ?Question $question = null;

    public ?int $selectedOptionId = null;

    public ?bool $isCorrect = null;

    public int $pointsAwarded = 0;

    public bool $answerLocked = false;

    #[On('countdown-finished')]
    public function countdownFinished(): void
    {
        $this->redirectRoute('frontend.quiz_live');
    }

    public function mount(): void
    {
        abort_unless(session()->has('quiz_session_id') && session()->has('participant_id'), 403);

        $quiz = QuizSession::query()->findOrFail(session('quiz_session_id'));
        abort_if($quiz->status !== 'live', 404);

        $participant = Participant::query()
            ->whereKey(session('participant_id'))
            ->where('quiz_session_id', $quiz->id)
            ->firstOrFail();

        $this->question = Question::with([
            'questionOptions' => fn ($query) => $query->orderBy('position'),
        ])->whereKey($quiz->current_question_id)
            ->where('question_set_id', $quiz->question_set_id)
            ->first();

        if (! $this->question) {
            return;
        }

        $answer = $participant->answers()
            ->where('question_id', $this->question->id)
            ->first();

        if ($answer) {
            $this->showResult($answer);
        }
    }

    public function submitAnswer(): void
    {
        if ($this->answerLocked) {
            return;
        }

        $this->validate([
            'selectedOptionId' => ['required', 'integer'],
        ], [
            'selectedOptionId.required' => 'Please select an answer.',
        ]);

        abort_unless($this->question, 404);

        $quiz = QuizSession::query()
            ->whereKey(session('quiz_session_id'))
            ->where('status', 'live')
            ->firstOrFail();

        abort_unless((int) $quiz->current_question_id === $this->question->id, 409);

        $participant = Participant::query()
            ->whereKey(session('participant_id'))
            ->where('quiz_session_id', $quiz->id)
            ->firstOrFail();

        $option = QuestionOption::query()
            ->whereKey($this->selectedOptionId)
            ->where('question_id', $this->question->id)
            ->firstOrFail();

        $answer = DB::transaction(function () use ($participant, $option): Answer {
            $answer = Answer::query()->firstOrCreate(
                [
                    'participant_id' => $participant->id,
                    'question_id' => $this->question->id,
                ],
                [
                    'question_option_id' => $option->id,
                    'is_correct' => $option->is_correct,
                    'points_awarded' => $option->is_correct ? $this->question->points : 0,
                ],
            );

            if ($answer->wasRecentlyCreated && $answer->points_awarded > 0) {
                $participant->increment('score', $answer->points_awarded);
            }

            return $answer;
        });

        $this->showResult($answer);
    }

    public function autoSubmitAnswer(): void
    {
        if ($this->answerLocked || ! $this->question) {
            return;
        }

        $this->answerLocked = true;

        $quiz = QuizSession::query()
            ->whereKey(session('quiz_session_id'))
            ->where('status', 'live')
            ->firstOrFail();

        abort_unless((int) $quiz->current_question_id === $this->question->id, 409);

        $participant = Participant::query()
            ->whereKey(session('participant_id'))
            ->where('quiz_session_id', $quiz->id)
            ->firstOrFail();

        $option = null;

        if ($this->selectedOptionId !== null) {
            $option = QuestionOption::query()
                ->whereKey($this->selectedOptionId)
                ->where('question_id', $this->question->id)
                ->firstOrFail();
        }

        $answer = DB::transaction(function () use ($participant, $option): Answer {
            $answer = Answer::query()->firstOrCreate(
                [
                    'participant_id' => $participant->id,
                    'question_id' => $this->question->id,
                ],
                [
                    'question_option_id' => $option?->id,
                    'is_correct' => $option?->is_correct ?? false,
                    'points_awarded' => $option?->is_correct ? $this->question->points : 0,
                ],
            );

            if ($answer->wasRecentlyCreated && $answer->points_awarded > 0) {
                $participant->increment('score', $answer->points_awarded);
            }

            return $answer;
        });

        $this->showResult($answer);
    }

    private function showResult(Answer $answer): void
    {
        $this->selectedOptionId = $answer->question_option_id;
        $this->isCorrect = $answer->is_correct;
        $this->pointsAwarded = $answer->points_awarded;
        $this->answerLocked = true;
    }

    public function render()
    {
        return $this->frontend(
            view('livewire.quiz-live')
        );
    }
}
