<?php

namespace Brightfox\Traits;

trait HasPhoto
{
    public function getPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return asset(self::PROFILE_PATH . self::DEFAULT_PROFILE_PIC);
        }
        return asset(self::PROFILE_PATH . $value);
    }
}