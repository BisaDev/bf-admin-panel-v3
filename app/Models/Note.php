<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'text'
    ];

    public function noteable()
    {
        return $this->morphTo();
    }
}
