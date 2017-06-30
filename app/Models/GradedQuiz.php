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
        'quiz_name', 'quiz_description', 'quiz_type', 'quiz_grade_level', 'quiz_subject', 'meetup_id'
    ];

    public function meetup()
    {
        return $this->belongsTo(Meetup::class);
    }
    
    public function questions()
    {
        return $this->hasMany(GradedQuizQuestion::class, 'graded_quiz_id');
    }
}
