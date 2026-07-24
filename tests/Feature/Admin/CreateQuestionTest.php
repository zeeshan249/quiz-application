<?php

use App\Livewire\Admin\CreateQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('starts with two options that can both be marked correct', function () {
    Livewire::test(CreateQuestion::class)
        ->assertSet('options', [
            ['text' => '', 'is_correct' => false],
            ['text' => '', 'is_correct' => false],
        ])
        ->assertSeeHtml('type="checkbox"')
        ->assertDontSee('Add Option')
        ->assertDontSee('Remove')
        ->set('options.0.is_correct', true)
        ->set('options.1.is_correct', true)
        ->assertSet('options.0.is_correct', true)
        ->assertSet('options.1.is_correct', true);
});
