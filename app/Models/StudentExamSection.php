<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExamSection extends Model
{
    const SECTIONS = [
        '1' => ['name' => 'Reading Comprehension', 'questions' => '52', 'timeAvailable' => '65'],
        '2' => ['name' => 'Writing and Language', 'questions' => '44', 'timeAvailable' => '35'],
        '3' => ['name' => 'Math-No Calculator', 'questions' => '20', 'timeAvailable' => '25'],
        '4' => ['name' => 'Math-With Calculator', 'questions' => '38', 'timeAvailable' => '55']
    ];

    protected $fillable = [
        'student_exam_id', 'section_number', 'number_correct', 'score'
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
