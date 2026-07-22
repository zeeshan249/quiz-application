<?php

namespace Database\Factories;

use App\Models\QuestionSet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuestionSet>
 */
class QuestionSetFactory extends Factory
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
            'description' => fake()->sentence(8),
            'created_by' => User::factory(),
        ];
    }
}
