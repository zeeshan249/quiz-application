<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\QuizSession;

final readonly class GetQuizSession
{
    public function handle(
        string $joincode
    ) {
        return QuizSession::query()
            ->where('join_code', $joincode)
            ->where('status', 'lobby')
            ->first();
    }
}
