<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Traits\HasPhoto;

class Minigame extends Model
{
    use HasPhoto;

    const PROFILE_PATH = "uploads/activities/";
    const DEFAULT_PHOTO = "default-image.png";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
