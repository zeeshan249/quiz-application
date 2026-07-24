<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSet extends Model
{
    use HasFactory;

    public function questions()
    {
        return $this->hasMany(
            Question::class,
            'question_set_id', // related table
            'id'               // current table
        );
    }
}
