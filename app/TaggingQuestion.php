<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingQuestion extends Model
{
    protected $fillable = [
        'tagging_subject_id', 'tagging_topic_id'
    ];

    public function subject() {
        return $this->hasOne(TaggingSubject::class);
    }

    public function topic () {
        return $this->hasOne(TaggingTopic::class);
    }
}
