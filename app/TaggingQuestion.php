<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingQuestion extends Model
{
    public function subject() {
        return $this->hasOne(TaggingSubject::class);
    }

    public function topic () {
        return $this->hasMany(TaggingTopic::class);
    }
}
