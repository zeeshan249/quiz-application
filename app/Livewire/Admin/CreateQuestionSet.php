<?php

namespace App\Livewire\Admin;

use App\Models\QuestionSet;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Questions Set')]

class CreateQuestionSet extends Component
{
    public string $title = '';

    public string $description = '';

    public ?QuestionSet $questionSet = null;

    protected function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
            ],
        ];
    }

    public function mount(?QuestionSet $questionSet = null): void
    {
        if ($questionSet && $questionSet->exists()) {
            $this->questionSet = $questionSet;
            $this->title = $questionSet->title ?? '';
            $this->description = $questionSet->description ?? '';

        }
    }

    public function render()
    {
        return view('livewire.admin.create-question-set');
    }

    public function save(): void
    {
        $validated = $this->validate();

        QuestionSet::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'created_by' => auth()->user()->id,
        ]);

        session()->flash('success', 'Question Set Created Successfully.');

        $this->redirectRoute('admin.questions-set', navigate: true);
    }

    public function update(): void
    {
        $validated = $this->validate();

        $this->questionSet->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
        ]);

        session()->flash('success', 'Question Set updated successfully.');

        $this->redirectRoute('admin.questions-set', navigate: true);
    }

    protected function messages()
    {
        return [
            'title.required' => 'The Title Is Mandatory',
        ];
    }
}
