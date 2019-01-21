<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Exam extends Model
{
    protected $fillable = [
        'type', 'subtype', 'test_id', 'source', 'description'
    ];

    public function getCreateTestIdAttribute()
    {
        if ($this->subtype) {
            $exams = Exam::all()->where('type', $this->type)->where('subtype', $this->subtype);
        } else {
            $exams = Exam::all()->where('type', $this->type);
            $exams->pop();
        }
        if ($exams->isNotEmpty()) {
            $lastExamId = $exams->last()->test_id;
            $typeLength = $this->subtype ? strlen($this->type) + strlen($this->subtype) + 1 : strlen($this->type);
            $lastExamId = substr($lastExamId, $typeLength + 1);
            $testId = $lastExamId + 1;
        } else {
            $testId = 1;
        }

        if ($this->subtype) {
            return "{$this->type}-{$this->subtype}-{$testId}";
        } else {
            return "{$this->type}-{$testId}";
        }
    }

    public function sections()
    {
        return $this->hasMany(ExamSection::class);
    }

    public function scoreTable()
    {
        return $this->hasOne(ExamScoreTable::class);
    }

    public function sectionsMetadata()
    {
        return $this->hasMany(ExamSectionMetadata::class, 'exam_type', 'type');
    }

    public function getIsMiniExamAttribute()
    {
        if ($this->type !== 'SAT' && $this->type !== 'ACT') {
            return true;
        } else {
            return false;
        }
    }

    public function getIsCompletedAttribute()
    {
        $student = Student::where('user_id', Auth::id())->first();
        $examId = $this->id;
        $completed = $student->exams->contains(function ($item) use($examId) {
            return $item->exam_id === $examId;
        });
        return $completed;
    }

    public function getNumberOfSectionsAttribute()
    {
        if ($this->IsMiniExam) {
           return 1;
        } else {
            return count($this->sectionsMetadata);
        }
    }
}
