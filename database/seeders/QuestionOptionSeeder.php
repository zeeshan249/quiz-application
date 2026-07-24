<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Seeder;

class QuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Question::all() as $question) {

            $correct = rand(1, 4);

            for ($i = 1; $i <= 4; $i++) {
                QuestionOption::create([
                    'question_id' => $question->id,
                    'text' => "Option {$i}",
                    'is_correct' => $i === $correct,
                    'position' => $i,
                ]);
            }
        }
    }
}
