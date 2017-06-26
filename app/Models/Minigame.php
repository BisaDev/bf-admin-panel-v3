<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Traits\HasPhoto;
use Brightfox\Traits\Noteable;
use File;

class Minigame extends Model
{
    use HasPhoto, Noteable;

    const PHOTO_PATH = "uploads/activities/";
    const DEFAULT_PHOTO = "default-image.png";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public static function boot() {
        parent::boot();
        
        self::deleting(function ($object) {
            if(!is_null($object->getOriginal('photo')) || $object->getOriginal('photo') != ''){
                File::delete(public_path(self::PHOTO_PATH . $object->getOriginal('photo')));
            }
        });
    }
}
