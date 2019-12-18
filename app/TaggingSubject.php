<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingSubject extends Model
{

    protected $fillable = [
        'name'
    ];

    public function topics () {
        return $this->hasMany(TaggingTopic::class);
    }
}
