<?php

declare(strict_types=1);

namespace App\Queries;

use App\Models\QuestionSet;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final readonly class ListQuestionSets
{
    public function handle(
        ?string $search = null,
        string $sortField = 'id',
        string $sortDirection = 'asc'
    ): LengthAwarePaginator {
        return QuestionSet::query()
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");

            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);
    }
}
