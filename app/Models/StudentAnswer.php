<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class StudentAnswer extends Model
{
    const PHOTO_PATH = "uploads/activities/";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer_id', 'answer_text', 'answer_image','is_correct', 'student_id', 'graded_quiz_question_id'
    ];
    
    public function graded_quiz_question()
    {
        return $this->belongsTo(GradedQuizQuestion::class);
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function getAnswerImageAttribute($value)
    {
        if (!$value || $value == ''){
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }
}
