<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionSet;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 1; $i <= 50; $i++) {
        //     QuestionSet::inRandomOrder()->first()
        //         ->questions()
        //         ->create([
        //             'text' => "Sample Question {$i}",
        //             'position' => $i,
        //             'points' => 1,
        //             'time_limit' => 20,
        //         ]);
        // }
        $questionSetIds = QuestionSet::pluck('id');

        for ($i = 1; $i <= 50; $i++) {
            Question::create([
                'question_set_id' => $questionSetIds->random(),
                'text' => "Sample Question {$i}",
                'position' => $i,
                'points' => 1,
                'time_limit' => 20,
            ]);
        }
    }
}
