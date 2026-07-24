<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'text',
        'is_correct',
        'position',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            Question::class,
            'question_id', // foreign key on CURRENT table (question_options)
            'id'           // owner key on RELATED table (questions)
        );
    }
}
