<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionSet extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(  // 3 parmaters
            Question::class,    // question table
            'question_set_id', // related table  Foreign Key
            'id'               // current table  Primary Key
        );
    }

    public function quizSessions(): HasMany
    {
        return $this->hasMany(QuizSession::class, 'question_set_id', 'id');
    }
}
// for question_set table  its pk is id which reffers to Question table question_set_id Has many
// question set e bose jodi question set er question id te point kory hasmnay te  tale related table e 2nd arg then 3rd arg nijer
