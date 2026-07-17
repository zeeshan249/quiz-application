<?php

namespace App\Livewire;

use App\Livewire\Components\FrontendComponent;


class UserWaitingLobby extends FrontendComponent
{
    public function render()
    {
        return view('livewire.user-waiting-lobby')
        ->layout('components.layouts.frontend');
    }
}
