<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingTopic extends Model
{
    protected $fillable = [
        'name' , 'tagging_subject_id'
    ];

    public function subject () {
        return $this->belongsTo(TaggingSubject::class);
    }
}
