<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class TaggingSubject extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    public function topic () {
        return $this->hasMany(TaggingTopic::class);
    }
}
