<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Brightfox\Subject;

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
        return $this->hasMany(Subject::class);
    }
}
