<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Traits\HasPhoto;

class UserDetail extends Model
{
    use HasPhoto;

    const PROFILE_PATH = "uploads/profiles/";
    const DEFAULT_PROFILE_PIC = "default-profile.jpg";

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
}
