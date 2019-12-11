<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingImage extends Model
{
    public function question () {
        return $this->hasOne(TaggingQuestion::class);
    }
}
