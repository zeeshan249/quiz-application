<?php

namespace App\Livewire\Admin;

use App\Models\QuestionSet;
use App\Models\QuizSession;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Create Question')]

class CreateQuestion extends Component
{
    use WithPagination;

    public string $title = '';

    public string $join_code = '';

    public string $status = '';

    public ?QuizSession $quizSession = null;

    public Collection $questionSets;

    public ?int $question_set_id = null;

    public array $options = [
        ['text' => '', 'is_correct' => false],
        ['text' => '', 'is_correct' => false],
    ];

    public function mount(?QuizSession $quizSession = null): void
    {
        $this->questionSets = QuestionSet::orderBy('title')->get();

    }

    public function render()
    {
        return view('livewire.admin.create-question');
    }
}
