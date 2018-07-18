<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSection extends Model
{
    protected $fillable = [
        'section_number', 'question_number', 'correct_1', 'correct_2', 'correct_3', 'correct_4', 'correct_5', 'topic'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
