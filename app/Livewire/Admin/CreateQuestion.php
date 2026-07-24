<?php

namespace App\Livewire\Admin;

use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionSet;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Create Question')]

class CreateQuestion extends Component
{
    public string $text = '';

    public ?int $question_set_id = null;

    public ?Question $question = null;

    public Collection $questionSets;

    public array $options = [
        [
            'id' => 1,
            'text' => '',
            'is_correct' => false,
        ],
        [
            'id' => 2,
            'text' => '',
            'is_correct' => false,
        ],
    ];

    protected function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
                'max:1000',
            ],
            'question_set_id' => [
                'required',
                'integer',
                'exists:question_sets,id',
            ],
            'options' => [
                'required',
                'array',
                'min:2',
                'max:6',
                function ($attribute, $value, $fail) {
                    $hasCorrect = collect($value)->contains(fn ($option) => $option['is_correct'] ?? false);

                    if (! $hasCorrect) {
                        $fail('At least one option must be marked as correct.');
                    }
                },
            ],
            'options.*.text' => [
                'required',
                'string',
                'max:255',
            ],
            'options.*.is_correct' => [
                'boolean',
            ],
        ];
    }

    protected function messages(): array
    {
        return [
            'text.required' => 'The question text is required.',
            'question_set_id.required' => 'Please select a question set.',
            'options.min' => 'A question must have at least :min options.',
            'options.max' => 'A question cannot have more than :max options.',
            'options.*.text.required' => 'Each option must have text.',
        ];
    }

    public function mount(?Question $question = null): void
    {
        $this->questionSets = QuestionSet::orderBy('title')->get();

        if ($question && $question->exists) {
            $this->question = $question;
            $this->text = $question->text;
            $this->question_set_id = $question->question_set_id;

            $question->load('questionOptions');

            $this->options = $question->questionOptions
                ->map(fn (QuestionOption $option) => [
                    'id' => $option->id,
                    'text' => $option->text,
                    'is_correct' => $option->is_correct,
                ])
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.admin.create-question');
    }

    public function save(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $question = Question::create([
                'question_set_id' => $validated['question_set_id'],
                'text' => $validated['text'],
                'position' => Question::max('position') + 1,
                'points' => 1,
            ]);

            foreach ($validated['options'] as $index => $option) {
                $question->questionOptions()->create([
                    'text' => $option['text'],
                    'is_correct' => $option['is_correct'],
                    'position' => $index,
                ]);
            }
        });

        session()->flash('success', 'Question created successfully.');

        $this->redirectRoute('admin.questions', navigate: true);
    }

    public function update(): void
    {
        $validated = $this->validate();

        DB::transaction(function () use ($validated) {
            $this->question->update([
                'question_set_id' => $validated['question_set_id'],
                'text' => $validated['text'],
            ]);

            $this->question->questionOptions()->delete();

            foreach ($validated['options'] as $index => $option) {
                $this->question->questionOptions()->create([
                    'text' => $option['text'],
                    'is_correct' => $option['is_correct'],
                    'position' => $index,
                ]);
            }
        });

        session()->flash('success', 'Question updated successfully.');

        $this->redirectRoute('admin.questions', navigate: true);
    }
}
