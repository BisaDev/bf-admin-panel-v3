<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\Taggable;

class Quiz extends Model
{
    use Eloquence, Taggable;

    const TYPES = [
        'Multiple choice: General',
        'Multiple choice: Two truths one lie', 
        'Multiple choice: Blitz', 
        'Multiple choice: Confidence level', 
        'Fill the blank', 
        'Trivia: Celebrity',
        'Trivia: Forbidden fruit',
        'Trivia: Questions',
        'Apple Pencil'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'type', 'subject_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'title', 'type', 'subject.name'
    ];

    public function getTypeAttribute($value)
    {
        return json_decode($value);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class)->withPivot('order')->orderBy('question_quiz.order', 'asc');
    }

    public function activity_buckets()
    {
        return $this->belongsToMany(ActivityBucket::class)->withPivot('minigame_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function getNumberOfQuestionsAttribute()
    {
        return $this->questions->count();
    }

    protected function getArrayableAppends()
    {
        $this->appends = array_unique(array_merge($this->appends, ['number_of_questions']));

        return parent::getArrayableAppends();
    }
}
