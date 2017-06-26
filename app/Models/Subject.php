<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class Subject extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'grade_level_id'
    ];

    /**
     * The attributes that will be searchable, can be relations.
     *
     * @var array
     */
    protected $searchableColumns = [
        'name'
    ];

    public function grade_level()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }
}
