<?php

namespace Brightfox\Traits;
use Brightfox\Models\Tag;

trait Taggable
{
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}