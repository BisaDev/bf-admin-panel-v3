<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamSection extends Model
{
    const SECTIONS = [
        '1' => ['name' => 'Reading Comprehension', 'questions' => '52'],
        '2' => ['name' => 'Writing and Language', 'questions' => '44'],
        '3' => ['name' => 'Math-No Calculator', 'questions' => '20'],
        '4' => ['name' => 'Math-With Calculator', 'questions' => '38']
    ];

    protected $fillable = [
        'student_exam_id', 'section_number', 'number_correct', 'score'
    ];

    public function exam()
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
