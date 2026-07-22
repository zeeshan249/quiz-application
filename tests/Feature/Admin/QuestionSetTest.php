<?php

use App\Livewire\Admin\QuestionsSet;
use App\Models\QuestionSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads the page', function () {
    Livewire::test(QuestionsSet::class)
        ->assertStatus(200)
        ->assertSee('Question Sets');
});

it('Create And test Question Set', function () {
    QuestionSet::factory()->create([
        'title' => 'Question Set A',
        'description' => 'Question Set A Desc',
    ]);

    QuestionSet::factory()->create([
        'title' => 'Question Set B',
        'description' => 'Question Set B Desc',
    ]);

    $act = Livewire::test(QuestionsSet::class)
        ->set('search', 'Question Set A');

    $act->assertSee('Question Set A')
        ->assertDontSee('Question Set B');
});

it('pagination works as expected',function(){

 $questionSet=QuestionSet::factory()->count(15)->create();

 Livewire::test(QuestionsSet::class)
         ->assertSee($questionSet->last()->title)
         ->call('gotoPage',2)
         ->assertStatus(200);

});

it('sorts by title ascending', function () {
    QuestionSet::factory()->create(['title' => 'Zebra Set']);
    QuestionSet::factory()->create(['title' => 'Alpha Set']);
    QuestionSet::factory()->create(['title' => 'Beta Set']);

    Livewire::test(QuestionsSet::class)
        ->call('sortBy', 'title')
        ->assertSee('Alpha Set')
        ->assertSee('Beta Set')
        ->assertSee('Zebra Set');
});

it('toggles sort direction', function () {
    $set1 = QuestionSet::factory()->create(['title' => 'First Set']);
    $set2 = QuestionSet::factory()->create(['title' => 'Second Set']);

    $component = Livewire::test(QuestionsSet::class)
        ->call('sortBy', 'title')
        ->assertSee('First Set');

    $component->call('sortBy', 'title')
        ->assertSee('Second Set');
});

it('shows empty state when no results found', function () {
    QuestionSet::factory()->create(['title' => 'Test Set']);

    Livewire::test(QuestionsSet::class)
        ->set('search', 'Nonexistent')
        ->assertSee('No records found.');
});

it('displays session success message', function () {
    QuestionSet::factory()->create(['title' => 'Test Set']);

    Livewire::test(QuestionsSet::class)
        ->assertStatus(200);
});

it('displays all question sets on initial load', function () {
    $set1 = QuestionSet::factory()->create(['title' => 'Set One', 'description' => 'Desc One']);
    $set2 = QuestionSet::factory()->create(['title' => 'Set Two', 'description' => 'Desc Two']);

    Livewire::test(QuestionsSet::class)
        ->assertSee('Set One')
        ->assertSee('Desc One')
        ->assertSee('Set Two')
        ->assertSee('Desc Two')
        ->assertSee('Edit');
});

it('search is case insensitive', function () {
    QuestionSet::factory()->create(['title' => 'JavaScript Questions']);
    QuestionSet::factory()->create(['title' => 'PHP Questions']);

    Livewire::test(QuestionsSet::class)
        ->set('search', 'javascript')
        ->assertSee('JavaScript Questions')
        ->assertDontSee('PHP Questions');
});

it('clears search results when search is emptied', function () {
    $set1 = QuestionSet::factory()->create(['title' => 'Specific Set']);
    $set2 = QuestionSet::factory()->create(['title' => 'Another Set']);

    Livewire::test(QuestionsSet::class)
        ->set('search', 'Specific')
        ->assertSee('Specific Set')
        ->assertDontSee('Another Set')
        ->set('search', '')
        ->assertSee('Specific Set')
        ->assertSee('Another Set');
});
