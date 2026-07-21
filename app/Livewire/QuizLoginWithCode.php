<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;
use App\Models\QuizSession;
use App\Queries\GetQuizSession;
use Livewire\Attributes\Url;

class QuizLoginWithCode extends FrontendComponent
{
    #[Url]
    public string $joincode = '';

    protected function rules(): array
    {
        return [
            'joincode' => [
                'required',
                'integer',
                'between:100000,999999',
            ],
        ];
    }

    public function join()
    {
        $validated = $this->validate();

        // $quizSession = QuizSession::where('join_code', $validated['joincode'])
        //     ->where('status', 'lobby')
        //     ->first();

        $quizSession = (new GetQuizSession)->handle(
            $this->joincode
        );
        if ($quizSession) {
            $this->redirectRoute(
                'frontend.name',
                ['joincode' => $validated['joincode']],
                navigate: true
            );

            return;
        }

        $this->addError(
            'joincode',
            'The join code is invalid or the quiz is not accepting participants.'
        );
    }

    public function render()
    {

        return $this->frontend(
            view('livewire.quiz-login-with-code')
        );
    }

    protected function messages(): array
    {
        return [
            'joincode.required' => 'The Code Is Required',
            'joincode.integer' => 'Code Must Be Integer',
            'joincode.between' => 'Code Must Be Exactly 6 digits',
        ];
    }
}
