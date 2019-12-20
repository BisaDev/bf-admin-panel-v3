<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingSubject extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function topics () {
        return $this->hasMany(TaggingTopic::class);
    }
}
