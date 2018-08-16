<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\HasFullName;
use Brightfox\Traits\HasPhoto;
use Brightfox\Traits\Noteable;
use File;

class FamilyMember extends Model
{
    use Eloquence, HasFullName, HasPhoto, Noteable;

    const PHOTO_PATH = "uploads/profiles/";
    const DEFAULT_PHOTO = "default-profile.jpg";
    const TYPES = ['parent', 'sibling', 'uncle / Aunt', 'grandparent', 'nanny / Au Pair', 'step-parent'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'middle_name', 'last_name', 'email', 'secondary_email', 'phone', 'mobile_phone', 'type', 'address', 'city', 'state', 'can_pickup', 'active', 'student_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name', 'middle_name', 'last_name', 'email', 'secondary_email', 'phone', 'mobile_phone', 'address', 'city', 'state'
    ];

    public function getTypeAttribute($value)
    {
        return json_decode($value);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
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
