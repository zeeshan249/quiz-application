<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::private('quiz.{quizSessionId}', function ($user, $quizSessionId) {
    return true;
});
