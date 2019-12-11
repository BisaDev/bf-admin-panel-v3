<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingSubject extends Model
{
    protected $table = 'tagging_subjects';

    public function topic () {
        return $this->hasMany(Tagging_Topic::class);
    }
}
