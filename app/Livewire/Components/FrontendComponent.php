<?php

namespace App\Livewire\Components;

use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class FrontendComponent extends Component
{
    protected function frontend(View $view): View
    {
        return $view->layout('components.layouts.frontend');
    }
}
