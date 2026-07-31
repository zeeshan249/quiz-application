<?php

use App\Livewire\QuizLive;
use App\Models\Participant;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionSet;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows one question and displays the result after submitting an answer', function () {
    $user = User::factory()->create();
    $questionSet = QuestionSet::create([
        'title' => 'General Knowledge',
        'created_by' => $user->id,
    ]);
    $question = Question::create([
        'question_set_id' => $questionSet->id,
        'text' => 'What is 2 + 2?',
        'position' => 1,
        'points' => 2,
    ]);
    $correctOption = QuestionOption::create([
        'question_id' => $question->id,
        'text' => '4',
        'position' => 1,
        'is_correct' => true,
    ]);
    QuestionOption::create([
        'question_id' => $question->id,
        'text' => '5',
        'position' => 2,
        'is_correct' => false,
    ]);
    $quiz = QuizSession::create([
        'title' => 'Math Quiz',
        'join_code' => 123456,
        'status' => 'live',
        'question_set_id' => $questionSet->id,
        'current_question_id' => $question->id,
    ]);
    $participant = Participant::create([
        'quiz_session_id' => $quiz->id,
        'name' => 'Alex',
        'token' => 'participant-token',
    ]);

    session([
        'quiz_session_id' => $quiz->id,
        'participant_id' => $participant->id,
    ]);

    Livewire::test(QuizLive::class)
        ->assertSee('What is 2 + 2?')
        ->assertSee('4')
        ->assertDontSee('No active question is available')
        ->set('selectedOptionId', $correctOption->id)
        ->call('submitAnswer')
        ->assertSet('isCorrect', true)
        ->assertSet('pointsAwarded', 2)
        ->assertSee('Correct!');

    expect($participant->refresh()->score)->toBe(2)
        ->and($participant->answers()->count())->toBe(1);
});

it('locks and records an unanswered question when time expires', function () {
    $user = User::factory()->create();
    $questionSet = QuestionSet::create([
        'title' => 'General Knowledge',
        'created_by' => $user->id,
    ]);
    $question = Question::create([
        'question_set_id' => $questionSet->id,
        'text' => 'What is 2 + 2?',
        'position' => 1,
        'points' => 2,
    ]);
    QuestionOption::create([
        'question_id' => $question->id,
        'text' => '4',
        'position' => 1,
        'is_correct' => true,
    ]);
    $quiz = QuizSession::create([
        'title' => 'Math Quiz',
        'join_code' => 123456,
        'status' => 'live',
        'question_set_id' => $questionSet->id,
        'current_question_id' => $question->id,
    ]);
    $participant = Participant::create([
        'quiz_session_id' => $quiz->id,
        'name' => 'Alex',
        'token' => 'participant-token',
    ]);

    session([
        'quiz_session_id' => $quiz->id,
        'participant_id' => $participant->id,
    ]);

    Livewire::test(QuizLive::class)
        ->call('autoSubmitAnswer')
        ->assertSet('answerLocked', true)
        ->assertSet('selectedOptionId', null)
        ->assertSet('isCorrect', false)
        ->assertSet('pointsAwarded', 0)
        ->assertSee('Incorrect');

    $answer = $participant->answers()->sole();

    expect($answer->question_option_id)->toBeNull()
        ->and($answer->is_correct)->toBeFalse()
        ->and($participant->refresh()->score)->toBe(0);
});

it('submits the selected answer when time expires', function () {
    $user = User::factory()->create();
    $questionSet = QuestionSet::create([
        'title' => 'General Knowledge',
        'created_by' => $user->id,
    ]);
    $question = Question::create([
        'question_set_id' => $questionSet->id,
        'text' => 'What is 2 + 2?',
        'position' => 1,
        'points' => 2,
    ]);
    $correctOption = QuestionOption::create([
        'question_id' => $question->id,
        'text' => '4',
        'position' => 1,
        'is_correct' => true,
    ]);
    $quiz = QuizSession::create([
        'title' => 'Math Quiz',
        'join_code' => 123456,
        'status' => 'live',
        'question_set_id' => $questionSet->id,
        'current_question_id' => $question->id,
    ]);
    $participant = Participant::create([
        'quiz_session_id' => $quiz->id,
        'name' => 'Alex',
        'token' => 'participant-token',
    ]);

    session([
        'quiz_session_id' => $quiz->id,
        'participant_id' => $participant->id,
    ]);

    Livewire::test(QuizLive::class)
        ->set('selectedOptionId', $correctOption->id)
        ->call('autoSubmitAnswer')
        ->assertSet('answerLocked', true)
        ->assertSet('isCorrect', true)
        ->assertSet('pointsAwarded', 2);

    expect($participant->refresh()->score)->toBe(2)
        ->and($participant->answers()->sole()->question_option_id)->toBe($correctOption->id);
});

it('renders the current question when the question advances', function () {
    $user = User::factory()->create();
    $questionSet = QuestionSet::create([
        'title' => 'General Knowledge',
        'created_by' => $user->id,
    ]);
    $firstQuestion = Question::create([
        'question_set_id' => $questionSet->id,
        'text' => 'What is 2 + 2?',
        'position' => 1,
        'points' => 2,
    ]);
    $nextQuestion = Question::create([
        'question_set_id' => $questionSet->id,
        'text' => 'What is 3 + 3?',
        'position' => 2,
        'points' => 2,
    ]);
    QuestionOption::create([
        'question_id' => $nextQuestion->id,
        'text' => '6',
        'position' => 1,
        'is_correct' => true,
    ]);
    $quiz = QuizSession::create([
        'title' => 'Math Quiz',
        'join_code' => 123456,
        'status' => 'live',
        'question_set_id' => $questionSet->id,
        'current_question_id' => $firstQuestion->id,
    ]);
    $participant = Participant::create([
        'quiz_session_id' => $quiz->id,
        'name' => 'Alex',
        'token' => 'participant-token',
    ]);

    session([
        'quiz_session_id' => $quiz->id,
        'participant_id' => $participant->id,
    ]);

    $component = Livewire::test(QuizLive::class)
        ->assertSee('What is 2 + 2?');

    $quiz->update(['current_question_id' => $nextQuestion->id]);

    $component
        ->call('onQuestionAdvanced', [])
        ->assertSet('question.id', $nextQuestion->id)
        ->assertDontSee('What is 2 + 2?')
        ->assertSee('What is 3 + 3?')
        ->assertSee('6');
});
