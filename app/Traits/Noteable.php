<?php

namespace Brightfox\Traits;
use Brightfox\Note;

trait Noteable
{
    public function notes()
    {
        return $this->morphMany(Note::class, 'model');
    }
}