<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScoreTable extends Model
{
    protected $fillable = [
        'exam_id', 'raw_score', 'math_section_score', 'reading_section_score', 'writing_section_score'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
