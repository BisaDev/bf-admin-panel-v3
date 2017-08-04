<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class GradedQuizQuestion extends Model
{
    const PHOTO_PATH = "uploads/activities/";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id', 'question_title', 'question_photo', 'question_topic', 'answers', 'tags', 'graded_quiz_id'
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
        $answers_array = json_decode($value);
        
        foreach($answers_array as $answer){
            if ($answer->original_photo != ''){
                $answer->original_photo = asset(self::PHOTO_PATH . $answer->original_photo);
            }
        }
        
        return $answers_array;
    }
    
    public function getQuestionPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }
}
