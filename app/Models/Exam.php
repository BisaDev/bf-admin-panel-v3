<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'type', 'test_id'
    ];

    public function getCreateTestIdAttribute()
    {
        return "{$this->type}-{$this->id}";
    }

    public function sections()
    {
        return $this->hasMany(ExamSection::class);
    }
}