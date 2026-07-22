<?php

namespace Database\Seeders;

use App\Models\QuestionSet;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user = User::first() ?? User::factory()->create();

        foreach (range('A', 'J') as $letter) {
            QuestionSet::create([
                'title' => "Question Set {$letter}",
                'description' => "Question Desc {$letter}",
                'created_by' => $user->id,
            ]);
        }
    }
}
