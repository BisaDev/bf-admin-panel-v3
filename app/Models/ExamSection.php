<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSection extends Model
{
    protected $fillable = [
        'exam_id', 'section_number', 'question_number', 'correct_1', 'correct_2', 'correct_3', 'correct_4', 'correct_5', 'correct_6', 'correct_7', 'correct_8', 'correct_9', 'topic'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
