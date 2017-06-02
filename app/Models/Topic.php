<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'subject_id'
    ];

    public function subject()
    {
        return $this->belongsTo('Brightfox\Subject');
    }
}
