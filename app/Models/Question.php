<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use File;

class Question extends Model
{
    use Eloquence;

    const PHOTO_PATH = "uploads/activities/";
    const DEFAULT_PHOTO = "default-image.png";
    const TYPES = ['Multiple choice', 'Fill the blank', 'Trivia'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'image', 'type', 'topic_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'title', 'type', 'topic.name'
    ];

    public function getPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }

    public function getTypeAttribute($value)
    {
        return json_decode($value);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
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
