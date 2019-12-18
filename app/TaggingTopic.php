<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class TaggingTopic extends Model
{
    use Eloquence;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name' , 'tagging_subject_id'
    ];

    protected $searchableColumns = [
        'name'
    ];

    public function subject () {
        return $this->belongsTo(TaggingSubject::class);
    }
}
