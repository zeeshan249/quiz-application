<?php

namespace App\Livewire\Admin;

use App\Queries\ListQuizSessions;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Quiz Sessions')]

class QuizSessions extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortField = 'id';

    public string $sortDirection = 'desc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        return view('livewire.admin.quiz-sessions', [
            'quizzes' => (new ListQuizSessions)->handle(
                $this->search,
                $this->sortField,
                $this->sortDirection
            ),
        ]);
    }
}
