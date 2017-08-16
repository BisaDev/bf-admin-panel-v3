<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class GradedQuiz extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id', 'quiz_title', 'quiz_description', 'quiz_type', 'quiz_grade_level', 'quiz_subject', 'meetup_id'
    ];

    public function meetup()
    {
        return $this->belongsTo(Meetup::class);
    }
    
    public function questions()
    {
        return $this->hasMany(GradedQuizQuestion::class, 'graded_quiz_id');
    }

    public function scopeByMeetupAndQuizId($query, $meetupId, $quizId)
    {
        return $query->where('quiz_id', $quizId)->where('meetup_id', $meetupId);
    }
    
    public function getQuizTypeAttribute($value)
    {
        return json_decode($value);
    }
}
