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
            'question_id', // foreign key on CURRENT table (question_options)   //foreign key
            'id'           // owner key on RELATED table (questions)            //pk
        );
    }
    // question_options table e bose question_id ta holo 2nd arg and 3rd arg holo refference table er in belongsTo
}
