<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamScoreTable extends Model
{
    protected $fillable = [
        'exam_id', 'score_table'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
