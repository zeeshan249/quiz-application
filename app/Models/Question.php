<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    public function questionSet(): BelongsTo
    {
        return $this->belongsTo(QuestionSet::class, 'question_set_id', 'id');
    }

    public function questionOptions(): HasMany
    {
        return $this->hasMany(QuestionOption::class,
            'question_id', // foreign key on related table question_options
            'id'           // local  key on current table questions
        );
    }
}
