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
}
