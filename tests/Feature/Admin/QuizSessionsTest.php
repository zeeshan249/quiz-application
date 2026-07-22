<?php

use App\Livewire\Admin\Login;
use App\Livewire\Admin\QuizSessions;
use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads the page', function () {
    Livewire::test(QuizSessions::class)
        ->assertStatus(200)
        ->assertSee('Quiz Sessions');
});

it('has default values', function () {
    Livewire::test(QuizSessions::class)
        ->assertSet('search', '')
        ->assertSet('sortField', 'id')
        ->assertSet('sortDirection', 'desc');
});
it('create and test quizzes', function () {

    QuizSession::factory()->create([
        'title' => 'Laravel Basics',
        'join_code' => random_int(100000,999999),
    ]);

    QuizSession::factory()->create([
        'title' => 'PHP Basics',
        'join_code' => random_int(100000,999999),
    ]);

    $act = Livewire::test(QuizSessions::class)
        ->set('search', 'Laravel');

    $act->assertSee('Laravel Basics')
        ->assertDontSee('PHP Basics');

});

it('pagination works as expected', function () {
    $quizzes = QuizSession::factory()->count(15)->create();

    Livewire::test(QuizSessions::class)
        ->assertSee($quizzes->last()->title) // Highest ID, shown first with DESC
        ->call('gotoPage', 2)
        ->assertStatus(200);
});

// it('allows an admin to login', function () {
//     // Arrange
//     User::factory()->create([
//         'email' => 'admin@example.com',
//         'password' => Hash::make('123456'),
//         'user_type' => 1,
//     ]);

//     // Act
//     $component = Livewire::test(Login::class)
//         ->set('email', 'admin@example.com')
//         ->set('password', '123456')
//         ->call('login');

//     // Assert
//     $component->assertRedirect(route('admin.dashboard'));

//     expect(Auth::check())->toBeTrue();
// });

// it('does not allow a non-admin to login', function () {
//     // Arrange
//     User::factory()->create([
//         'email' => 'user@example.com',
//         'password' => Hash::make('123456'),
//         'user_type' => 0,
//     ]);

//     // Act
//     $component = Livewire::test(Login::class)
//         ->set('email', 'user@example.com')
//         ->set('password', '123456')
//         ->call('login');

//     // Assert
//     $component->assertHasErrors(['credentials']);

//     // This will fail
//     expect(Auth::check())->toBeFalse();
// });
it('shows an error for invalid credentials', function () {
    // Arrange
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('123456'),
        'user_type' => 1,
    ]);

    // Act
    $component = Livewire::test(Login::class)
        ->set('email', 'admin@example.com')
        ->set('password', 'wrong-password')
        ->call('login');

    // Assert
    $component->assertHasErrors(['credentials']);

    expect(Auth::check())->toBeFalse();
});

it('validates required email and password fields', function () {
    // Arrange

    // Act
    $component = Livewire::test(Login::class)
        ->set('email', '')
        ->set('password', '')
        ->call('login');

    // Assert
    $component->assertHasErrors(['email', 'password']);
});
