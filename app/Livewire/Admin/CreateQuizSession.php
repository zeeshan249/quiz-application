<?php

namespace App\Livewire\Admin;

use App\Models\QuizSession;
use App\Models\QuestionSet;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Collection;

#[Title('Quiz Sessions')]

class CreateQuizSession extends Component
{
    use WithPagination;

    public string $title = '';

    public string $join_code = '';

    public string $status = 'draft';

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
                Rule::in(['draft', 'lobby', 'live']),
            ],
            'question_set_id' => [
                'nullable'
            ]
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

        session()->flash('success', 'Quiz session updated successfully.');

        $this->redirectRoute('admin.quiz-sessions', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.create-quiz-session');
    }
}
