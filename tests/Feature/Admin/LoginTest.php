<?php

use App\Livewire\Admin\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('loads the login page', function () {
    // Arrange

    // Act
    $component = Livewire::test(Login::class);

    // Assert
    $component
        ->assertStatus(200)
        ->assertSee('Admin Login');
});

it('allows an admin to login', function () {
    // Arrange
    User::factory()->create([
        'email' => 'admin@example.com',
        'password' => Hash::make('123456'),
        'user_type' => 1,
    ]);

    // Act
    $component = Livewire::test(Login::class)
        ->set('email', 'admin@example.com')
        ->set('password', '123456')
        ->call('login');

    // Assert
    $component->assertRedirect(route('admin.dashboard'));

    expect(Auth::check())->toBeTrue();
});

it('does not allow a non-admin to login', function () {
    // Arrange
    User::factory()->create([
        'email' => 'user@example.com',
        'password' => Hash::make('123456'),
        'user_type' => 0,
    ]);

    // Act
    $component = Livewire::test(Login::class)
        ->set('email', 'user@example.com')
        ->set('password', '123456')
        ->call('login');

    // Assert
    $component->assertHasErrors(['credentials']);

    // This will fail
    expect(Auth::check())->toBeFalse();
});
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
