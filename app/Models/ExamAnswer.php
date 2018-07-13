<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    protected $fillable = [
        'student_exam_section_id', 'question_number', 'answer', 'guessed'
    ];
}
