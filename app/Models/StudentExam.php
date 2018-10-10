<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'exam_id', 'student_id', 'number_correct', 'score'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function sections()
    {
        return $this->hasMany(StudentExamSection::class);
    }

    public function getTotalQuestionsAttribute()
    {
        $sections = StudentExamSection::SECTIONS;
        $completedSections = $this->sections->unique('section_number')->pluck('section_number');
        $totalQuestions = 0;
        foreach ($completedSections->all() as $completedSection) {
            $totalQuestions = $totalQuestions + $sections[$completedSection]['questions'];
        }
        return $totalQuestions;
    }
}
