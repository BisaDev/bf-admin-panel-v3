<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Subject extends Model
{
    use Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'grade_level_id'
    ];

    public function grade_level()
    {
        return $this->belongsTo('Brightfox\GradeLevel');
    }

    public function topics()
    {
        return $this->hasMany('Brightfox\Topic');
    }
}
