<?php

namespace Database\Factories;

use App\Models\QuizSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizSession>
 */
class QuizSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'title' => fake()->sentence(3),
            'join_code' => random_int(100000, 999999),
            'status' => 'draft',
            'created_by' => User::factory(),

            'answer_seconds' => fake()->randomElement([15, 20, 30, 45]),
            'reveal_seconds' => fake()->randomElement([5, 6, 8, 10]),

            'phase' => null,
            'phase_ends_at' => null,

            'started_at' => null,
            'ended_at' => null,
        ];
    }
}
