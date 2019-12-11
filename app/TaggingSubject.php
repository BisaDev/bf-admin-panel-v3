<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingSubject extends Model
{
    public function topic () {
        return $this->hasMany(TaggingTopic::class);
    }
}
