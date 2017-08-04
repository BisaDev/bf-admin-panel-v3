<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
    
    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected $visible = ['name'];
    
    public function quizzes()
    {
        return $this->morphedByMany(Quiz::class, 'taggable');
    }
}
