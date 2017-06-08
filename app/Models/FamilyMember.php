<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\Traits\HasFullName;
use Brightfox\Traits\HasPhoto;

class FamilyMember extends Model
{
    use Eloquence, HasFullName, HasPhoto;

    const PROFILE_PATH = "uploads/profiles/";
    const DEFAULT_PROFILE_PIC = "default-profile.jpg";

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

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
