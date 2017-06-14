<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'type', 'subject_id'
    ];

    public function getTypeAttribute($value)
    {
        return json_decode($value);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class)->withPivot('order')->orderBy('question_quiz.order', 'asc');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
