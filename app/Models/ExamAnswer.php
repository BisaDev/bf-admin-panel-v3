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

    public function getAnswerResultAttribute()
    {
        $correctAnswer = $this->correctAnswer;
        if(strtoupper($this->answer) === strtoupper($correctAnswer->correct_1) || $this->answer === $correctAnswer->correct_2 || $this->answer === $correctAnswer->correct_3 || $this->answer === $correctAnswer->correct_4 || $this->answer === $correctAnswer->correct_5 || $this->answer === $correctAnswer->correct_6 || $this->answer === $correctAnswer->correct_7 || $this->answer === $correctAnswer->correct_8 || $this->answer === $correctAnswer->correct_9) {
            return true;
        } else {
            return false;
        }
    }

    public function getBackgroundForReportAttribute()
    {
        if ($this->guessed) {
           if ($this->AnswerResult) {
               return 'd-guessed-right';
           } else {
               return 'b-guessed-wrong';
           }
        } else {
            if ($this->AnswerResult) {
                return 'e-right';
            } else {
                if($this->understood) {
                    return 'c-wrong-understood';
                } else {
                    return 'a-wrong';
                }
            }
        }
    }
}
