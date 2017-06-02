<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    public function subjects()
    {
        return $this->hasMany('Brightfox\Subject');
    }
}
