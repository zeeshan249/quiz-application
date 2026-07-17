<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\QuizSession;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListQuizSessions
{
    public function handle(
        ?string $search = null,
        string $sortField = 'id',
        string $sortDirection = 'asc'
    ): LengthAwarePaginator {
        return QuizSession::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('join_code', 'like', "%{$search}%");
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);
    }
}
