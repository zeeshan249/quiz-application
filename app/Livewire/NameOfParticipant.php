<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\Participant;
use App\Models\QuizSession;
use App\Queries\GetQuizSession;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class NameOfParticipant extends FrontendComponent
{
    #[Url]
    public string $joincode = '';

    public string $participantname = '';

    public ?QuizSession $quizSession = null;
   
    protected function rules(): array
    {
        return [
            'participantname' => [
                'required',
            ],
        ];
    }

    public function mount(): void
    {
        if (empty($this->joincode)) {
            return;
        }

        // fetch quiz session
        $this->quizSession = (new GetQuizSession)->handle(
            $this->joincode
        );
    }

    public function participantjoin()
    {
        $validated = $this->validate();

        if (! $this->quizSession) {
            $this->addError(
                'participantname',
                'Quiz session not found. Please use a valid join code.'
            );

            return;
        }

        $create = Participant::create([
            'quiz_session_id' => $this->quizSession->id,
            'name' => $validated['participantname'],
            'token' => Str::uuid()->toString(),
            'score' => 0,
            'joined_at' => now(),
        ]);

           if ($create) {
            $this->redirectRoute(
                'frontend.user_lobby',
                ['joincode' => $this->quizSession->joincode,
                 'name' => $validated['participantname']
                ],
                navigate: true
            );

            return;
        }

     

    }

    public function render()
    {
        return $this->frontend(
            view('livewire.name-of-participant')
        );
    }

    protected function messages(): array
    {
        return [
            'participantname.required' => 'Your Name is Required',
        ];
    }
}
