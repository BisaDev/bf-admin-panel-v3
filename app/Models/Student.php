<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\HasFullName;
use Brightfox\Traits\HasPhoto;
use Brightfox\Traits\Noteable;
use File;

class Student extends Model
{
    use Eloquence, HasFullName, HasPhoto, Noteable;

    const PHOTO_PATH = "uploads/profiles/";
    const DEFAULT_PHOTO = "default-profile.jpg";
    const GENDERS = ['Male', 'Female', 'Non specified'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'middle_name', 'last_name', 'birthdate', 'cohort_tag', 'gender', 'school_year', 'current_school', 'teacher', 'former_school', 'location_id', 'user_id'
    ];

    protected $dates = [
        'birthdate'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name', 'last_name', 'gender', 'current_school', 'school_year', 'teacher', 'former_school', 'location.name'
    ];

    public function getGenderAttribute($value)
    {
        return json_decode($value);
    }

    public function family_members()
    {
        return $this->hasMany(FamilyMember::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function meetups()
    {
        return $this->belongsToMany(Meetup::class)->orderBy('meetups.start_time', 'desc');
    }
    
    public function meetup_student_pivot($meetup_id)
    {
        return $this->hasMany(MeetupStudent::class)->where('meetup_student.meetup_id', $meetup_id);
    }
    
    public function graded_answer($graded_quiz_question_id)
    {
        return $this->hasMany(StudentAnswer::class, 'student_id')->where('student_answers.graded_quiz_question_id', $graded_quiz_question_id);
    }
    
    public function graded_answers($graded_quiz_id)
    {
        return $this->hasMany(StudentAnswer::class, 'student_id')
            ->leftJoin('graded_quiz_questions','graded_quiz_questions.id','=','student_answers.graded_quiz_question_id')
            ->where('graded_quiz_questions.graded_quiz_id', $graded_quiz_id);
    }

    public function exams()
    {
        return $this->hasMany(StudentExam::class);
    }

    public static function boot() {
        parent::boot();
        
        self::deleting(function ($object) {
            if(!is_null($object->getOriginal('photo')) || $object->getOriginal('photo') != ''){
                File::delete(public_path(self::PHOTO_PATH . $object->getOriginal('photo')));
            }
        });
    }
}
