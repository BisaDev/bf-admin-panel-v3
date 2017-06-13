<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Traits\HasPhoto;
use File;

class UserDetail extends Model
{
    use HasPhoto;

    const PHOTO_PATH = "uploads/profiles/";
    const DEFAULT_PHOTO = "default-profile.jpg";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'secondary_email', 'phone', 'mobile_phone', 'location_id', 'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
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
