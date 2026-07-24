<?php

namespace App\Livewire\Admin;

use App\Queries\ListQuestions;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Questions')]

class Question extends Component
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

        return view('livewire.admin.question', [
            'questions' => (new ListQuestions)->handle(
                $this->search,
                $this->sortField,
                $this->sortDirection,
            ),
        ]);
    }
}
