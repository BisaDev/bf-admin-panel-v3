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
        $examType = Exam::find($this->exam_id)->type;
        $examTypeSections = ExamSectionMetadata::where('exam_type', $examType)->get();
        $completedSections = $this->sections->unique('section_number')->pluck('section_number');
        $totalQuestions = $examTypeSections->whereIn('section_number', $completedSections)->pluck('questions')->sum();
        return $totalQuestions;
    }

    public function getTotalTimeAvailableAttribute()
    {
        $examType = Exam::find($this->exam_id)->type;
        $examTypeSections = ExamSectionMetadata::where('exam_type', $examType)->get();
        $completedSections = $this->sections->unique('section_number')->pluck('section_number');
        $totalTime = $examTypeSections->whereIn('section_number', $completedSections)->pluck('time_available')->sum();
        return $totalTime;
    }
}
