<?php

namespace Brightfox\Traits;
use Brightfox\Tag;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}