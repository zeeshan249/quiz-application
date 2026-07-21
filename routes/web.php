<?php

use App\Livewire\Admin\CreateQuizSession;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\QuizSessions;
use App\Livewire\NameOfParticipant;
use App\Livewire\QuizLoginWithCode;
use App\Livewire\UserWaitingLobby;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', QuizLoginWithCode::class)->name('frontend.login');
Route::get('/name-of-participant', NameOfParticipant::class)->name('frontend.name');
Route::get('/waiting-for-others', UserWaitingLobby::class)->name('frontend.user_lobby');

// API endpoint for participant count
Route::get('/api/quiz-session/{quizSessionId}/participant-count', function ($quizSessionId) {
    $count = Participant::where('quiz_session_id', $quizSessionId)->count();

    return response()->json(['count' => $count]);
});

// Only reachable when logged OUT. Authenticated admins are bounced to the
// dashboard (redirectUsersTo), and no-cache stops the back button from
// showing a stale login page after sign-in.
Route::middleware(['guest', 'no-cache'])->group(function () {
    Route::get('/admin/login', Login::class)->name('admin.login');
});

// Only reachable when logged IN as an admin (user_type === 1). Guests and
// non-admins are sent to login by the `admin` middleware, and no-cache stops
// the back button from showing a stale dashboard after logout.
Route::middleware(['admin', 'no-cache'])->group(function () {
    Route::get('/admin/dashboard', Dashboard::class)->name('admin.dashboard');
    Route::get('/admin/quiz-sessions', QuizSessions::class)->name('admin.quiz-sessions');
    Route::get('/admin/create-quiz-session', CreateQuizSession::class)->name('admin.quiz-sessions.create');
    Route::get('/quiz-sessions/{quizSession}/edit', CreateQuizSession::class)
        ->name('admin.quiz-sessions.edit');

    // Logs the admin out, kills the session, and rotates the CSRF token
    // (mirrors the session handling in the Login component).
    Route::post('/admin/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    })->name('admin.logout');
});
