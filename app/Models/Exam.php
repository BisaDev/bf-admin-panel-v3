<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'type', 'test_id', 'source', 'description'
    ];

    public function getCreateTestIdAttribute()
    {
        $exams = Exam::all()->where('type', $this->type);
        $exams->pop();
        if ($exams->isNotEmpty()) {
            $lastExamId = $exams->last()->test_id;
            $lastExamId = substr($lastExamId, 4);
            $testId = $lastExamId + 1;
        } else {
            $testId = 1;
        }

        return "{$this->type}-{$testId}";
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
}
