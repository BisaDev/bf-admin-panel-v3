<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class GradedQuizQuestion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'question_title', 'question_photo', 'question_topic', 'answers', 'graded_quiz_id'
    ];

    public function graded_quiz()
    {
        return $this->belongsTo(GradedQuiz::class);
    }

    public function student_answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }

    public function scopeFindByQuestionId($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }
    
    public function getAnswersAttribute($value)
    {
        return json_decode($value);
    }
    
    public function getQuestionPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }
}
