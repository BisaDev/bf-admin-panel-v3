<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use File;

class Answer extends Model
{
    const PHOTO_PATH = "uploads/activities/";
    const DEFAULT_PHOTO = "default-image.png";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text', 'type', 'is_correct', 'question_id'
    ];

    public function getPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
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
