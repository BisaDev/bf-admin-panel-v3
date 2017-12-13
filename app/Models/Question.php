<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\Taggable;
use File;

class Question extends Model
{
    use Eloquence, Taggable;

    const PHOTO_PATH = "uploads/activities/";
    const DEFAULT_PHOTO = "default-image.png";
    const TYPES = [
        'Multiple choice',
        'Fill the blank',
        'Trivia',
        'PenPal',
        'Drag and drop',
        'Tap Time',
        'Research and report back'
    ];

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

    protected $appends = [
        'date_created'
    ];

    public function getTitleAttribute($value)
    {
        return str_replace('[#blank]', '_____', $value);
    }

    public function getPhotoAttribute($value)
    {
        if (!$value || $value == '') {
            return $value;
        }
        return asset(self::PHOTO_PATH . $value);
    }

    public function getTypeAttribute($value)
    {
        return json_decode($value);
    }

    public function getDateCreatedAttribute()
    {
        return $this->created_at->format('m/d/Y');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class);
    }

    public function getAnswersHaveImagesAttribute()
    {
        $have_images = false;
        foreach ($this->answers as $answer) {
            if ($answer->photo) {
                $have_images = true;
                break;
            }
        }

        return $have_images;
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
