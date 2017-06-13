<?php

namespace Brightfox\Traits;

trait HasPhoto
{
    public function getPhotoAttribute($value)
    {
        if (!$value || $value == ''){
            return asset(self::PHOTO_PATH . self::DEFAULT_PHOTO);
        }
        return asset(self::PHOTO_PATH . $value);
    }
}