<?php

namespace App\Livewire\Admin;

use App\Events\QuestionAdvanced;
use App\Events\QuizEnded;
use App\Events\QuizStarted;
use App\Models\Question;
use App\Models\QuestionSet;
use App\Models\QuizSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Quiz Sessions')]

class CreateQuizSession extends Component
{
    use WithPagination;

    public string $title = '';

    public string $join_code = '';

    public string $status = '';

    public ?QuizSession $quizSession = null;

    public Collection $questionSets;

    public ?int $question_set_id = null;

    protected function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('quiz_sessions', 'title')->ignore($this->quizSession),
            ],
            'join_code' => [
                'required',
                'integer',
                'between:100000,999999',
                Rule::unique('quiz_sessions', 'join_code')->ignore($this->quizSession),
            ],
            'status' => [
                'required',
                Rule::in(['draft', 'lobby', 'live', 'ended']),
            ],
            'question_set_id' => [
                'required',
            ],
        ];
    }

    public function mount(?QuizSession $quizSession = null): void
    {
        $this->questionSets = QuestionSet::orderBy('title')->get();
        if ($quizSession && $quizSession->exists) {
            $this->quizSession = $quizSession;

            $this->title = $quizSession->title;
            $this->join_code = $quizSession->join_code;
            $this->status = $quizSession->status;
            $this->question_set_id = $quizSession->question_set_id;

        }
    }

    protected function messages(): array
    {
        return [
            'title.unique' => 'A quiz session with this Name already exists.',
            'join_code.integer' => 'The join code must be a  number.',
            'join_code.unique' => 'This join code is already in use.',
            'join_code.between' => 'The join code must be exactly 6 digits.',
            'question_set_id.required' => 'Please Select A Question',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        QuizSession::create([
            'title' => $validated['title'],
            'join_code' => $validated['join_code'],
            'status' => $validated['status'],
            'question_set_id' => $validated['question_set_id'],
            'created_by' => auth()->id(),
        ]);

        session()->flash('success', 'Quiz session created successfully.');

        $this->redirectRoute('admin.quiz-sessions', navigate: true);
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->quizSession->update([
            'title' => $validated['title'],
            'join_code' => $validated['join_code'],
            'status' => $validated['status'],
            'question_set_id' => $validated['question_set_id'],
        ]);

        if ($validated['status'] === 'live') {

            $firstQuestion = $this->quizSession
                ->questionSet
                ->questions()
                ->orderBy('position')
                ->first();

            abort_if(! $firstQuestion, 422, 'No questions found in the selected question set.');

            // Store the start time in a variable
            $startedAt = now()->addSeconds(20);

            $this->quizSession->update([
                'started_at' => $startedAt,
                'current_question_id' => $firstQuestion->id,
                'phase' => 'question',
                'phase_ends_at' => $startedAt->copy()->addSeconds($this->quizSession->answer_seconds),
            ]);

            logger()->info('Broadcasting QuizStarted', [
                'quiz_session_id' => $this->quizSession->id,
                'started_at' => $startedAt->timestamp,
            ]);

            broadcast(new QuizStarted(
                $this->quizSession->id,
                $startedAt->timestamp,
            ));

            logger()->info('QuizStarted broadcast sent');
        }

        session()->flash('success', 'Quiz session updated successfully.');

        $this->redirectRoute('admin.quiz-sessions', navigate: true);
    }



    /**
     * Advance the live quiz to the next question in the selected question set,
     * or mark it ended if there are no more questions left.
     */
    public function nextQuestion(): void
    {
        abort_unless($this->quizSession && $this->quizSession->exists, 404);
        abort_unless($this->quizSession->status === 'live', 409, 'Quiz session is not live.');
 
        $currentPosition = $this->quizSession->current_question_id
            ? Question::query()
             ->whereKey($this->quizSession->current_question_id)->value('position')
            : null;
 
        $nextQuestion = Question::query()
            ->where('question_set_id', $this->quizSession->question_set_id)
            ->when(
                $currentPosition !== null,
                fn ($query) => $query->where('position', '>', $currentPosition)
            )
            ->orderBy('position')
            ->first();
 
        // No more questions left — end the quiz.
        if (! $nextQuestion) {
            $this->quizSession->update([
                'status' => 'ended',
                'current_question_id' => null,
                'phase' => 'ended',
                'phase_ends_at' => null,
            ]);
 
            logger()->info('Broadcasting QuizEnded', [
                'quiz_session_id' => $this->quizSession->id,
            ]);
 
            broadcast(new QuizEnded($this->quizSession->id));
 
            session()->flash('success', 'Quiz ended — no more questions in this set.');
 
            return;
        }
 
        $phaseEndsAt = now()->addSeconds($this->quizSession->answer_seconds);
 
        $this->quizSession->update([
            'current_question_id' => $nextQuestion->id,
            'phase' => 'question',
            'phase_ends_at' => $phaseEndsAt,
        ]);
 
        logger()->info('Broadcasting QuestionAdvanced', [
            'quiz_session_id' => $this->quizSession->id,
            'question_id' => $nextQuestion->id,
            'phase_ends_at' => $phaseEndsAt->timestamp,
        ]);
 
        broadcast(new QuestionAdvanced(
            $this->quizSession->id,
            $nextQuestion->id,
            $phaseEndsAt->timestamp,
        ));
 
        session()->flash('success', 'Advanced to question #'.$nextQuestion->id.'.');
    }

    public function render()
    {
        return view('livewire.admin.create-quiz-session');
    }
}
