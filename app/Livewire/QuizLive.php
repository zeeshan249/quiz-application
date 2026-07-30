<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;


class QuizLive extends FrontendComponent
{
    public function render()
    {
          return $this->frontend(
            view('livewire.quiz-live')
        );
    }
}
