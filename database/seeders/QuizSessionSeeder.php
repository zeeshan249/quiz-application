<?php

namespace Database\Seeders;

use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = User::query()->value('id');

        $statuses = ['draft', 'lobby', 'live', 'ended'];
        $phases = ['question', 'reveal'];

        for ($i = 1; $i <= 100; $i++) {
            $status = fake()->randomElement($statuses);

            $startedAt = fake()->optional()->dateTimeBetween('-30 days', 'now');
            $endedAt = $status === 'ended'
                ? fake()->dateTimeBetween($startedAt ?? '-10 days', 'now')
                : null;

            QuizSession::create([
                'title' => "Quiz Session {$i}",
                'join_code' => random_int(100000, 999999),
                'status' => $status,
                'created_by' => $userId,

                'answer_seconds' => fake()->randomElement([15, 20, 30, 45]),
                'reveal_seconds' => fake()->randomElement([5, 6, 8, 10]),

                'phase' => $status === 'live'
                    ? fake()->randomElement($phases)
                    : null,

                'phase_ends_at' => $status === 'live'
                    ? fake()->dateTimeBetween('now', '+2 minutes')
                    : null,

                'started_at' => $startedAt,
                'ended_at' => $endedAt,
            ]);
        }
    }
}
