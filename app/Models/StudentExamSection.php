<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamSection extends Model
{
    protected $fillable = [
        'student_exam_id', 'section_number', 'number_correct', 'score'
    ];
}
