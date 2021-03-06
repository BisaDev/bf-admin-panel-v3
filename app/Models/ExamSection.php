<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSection extends Model
{
    protected $fillable = [
        'exam_id', 'section_number', 'question_number', 'correct_1', 'correct_2', 'correct_3', 'correct_4', 'correct_5', 'correct_6', 'correct_7', 'correct_8', 'correct_9', 'topic', 'explanation',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function metadata()
    {
        $examType = $this->exam->type;
        return $this->hasOne(ExamSectionMetadata::class, 'section_number', 'section_number')
            ->where('exam_type', $examType);
    }

    public function getExplanationImageAttribute($value)
    {
        if (!$value || $value == '') {
            return $value;
        }
        return asset('uploads/activities/' . $value);
    }

    public static function boot()
    {
        parent::boot();

        self::deleting(function ($object) {
            if (!is_null($object->getOriginal('photo')) || $object->getOriginal('photo') != '') {
                File::delete(public_path(self::PHOTO_PATH . $object->getOriginal('photo')));
            }
        });
    }
}
