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
        return "{$this->type}-{$this->id}";
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
