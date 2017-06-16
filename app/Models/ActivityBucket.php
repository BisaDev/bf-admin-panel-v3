<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class ActivityBucket extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'subject_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'title', 'subject.name'
    ];

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class)->withPivot('order', 'minigame_id')->orderBy('activity_bucket_quiz.order', 'asc');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
