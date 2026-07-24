<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\Question;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListQuestions
{
    public function handle(
        ?string $search = null,
        string $sortField = 'id',
        string $sortDirection = 'asc'
    ): LengthAwarePaginator {
        return Question::query()
            ->when($search, function ($query) use ($search) {
                $query->where('text', 'like', "%{$search}%");

            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);
    }
}
