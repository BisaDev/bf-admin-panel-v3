<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamSection extends Model
{
    const SECTIONS = [
        '1' => ['id' => '1', 'name' => 'Reading Comprehension', 'questions' => '52', 'openQuestions' => '0', 'timeAvailable' => '65', 'tableScore' => 'Reading Section Score'],
        '2' => ['id' => '2', 'name' => 'Writing and Language', 'questions' => '44', 'openQuestions' => '0', 'timeAvailable' => '35', 'tableScore' => 'Writing Section Score'],
        '3' => ['id' => '3', 'name' => 'Math-No Calculator', 'questions' => '20', 'openQuestions' => '5', 'timeAvailable' => '25', 'tableScore' => 'Math Section Score'],
        '4' => ['id' => '4', 'name' => 'Math-With Calculator', 'questions' => '38', 'openQuestions' => '8', 'timeAvailable' => '55', 'tableScore' => 'Math Section Score']
    ];

    protected $fillable = [
        'student_exam_id', 'section_number', 'number_correct', 'score', 'math_completed'
    ];

    public function studentExam()
    {
        return $this->belongsTo(StudentExam::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamAnswer::class);
    }
}
