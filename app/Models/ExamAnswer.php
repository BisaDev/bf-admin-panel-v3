<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamAnswer extends Model
{
    protected $fillable = [
        'student_exam_section_id', 'question_number', 'answer', 'guessed', 'understood'
    ];

    public function section()
    {
        return $this->belongsTo(StudentExamSection::class);
    }

    public function correctAnswer()
    {
        $studentExamSection = StudentExamSection::find($this->student_exam_section_id);
        $studentExam = StudentExam::find($studentExamSection->student_exam_id);
        $examId = $studentExam->exam_id;
        $section = $studentExamSection->section_number;

        return $this->hasOne(ExamSection::class, 'question_number', 'question_number')
            ->where('exam_id', $examId)
            ->where('section_number', $section);
    }
}