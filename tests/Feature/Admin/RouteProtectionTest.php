<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests away from the dashboard to the login page', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect('/admin/login');
});

it('allows an authenticated admin to view the dashboard', function () {
    $admin = User::factory()->create(['user_type' => 1]);

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk();
});

it('blocks an authenticated non-admin from the dashboard and logs them out', function () {
    $user = User::factory()->create(['user_type' => 0]);

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertRedirect('/admin/login');

    expect(auth()->check())->toBeFalse();
});

it('redirects an authenticated user away from the login page to the dashboard', function () {
    $admin = User::factory()->create(['user_type' => 1]);

    $this->actingAs($admin)
        ->get('/admin/login')
        ->assertRedirect('/admin/dashboard');
});

it('sends no-store cache headers on the login page so the back button cannot show a stale page', function () {
    $response = $this->get('/admin/login');

    $response->assertOk();
    expect($response->headers->get('Cache-Control'))->toContain('no-store');
});

it('sends no-store cache headers on the dashboard so the back button cannot show it after logout', function () {
    $admin = User::factory()->create(['user_type' => 1]);

    $response = $this->actingAs($admin)->get('/admin/dashboard');

    $response->assertOk();
    expect($response->headers->get('Cache-Control'))->toContain('no-store');
});
