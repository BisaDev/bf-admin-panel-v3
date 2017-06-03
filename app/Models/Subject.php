<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;
use Brightfox\GradeLevel, Brightfox\Topic;

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
    protected $searchableColumns = ['name'];

    public function grade_level()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
