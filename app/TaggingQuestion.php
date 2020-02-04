<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingQuestion extends Model
{
    protected $fillable = [
        'tagging_subject_id', 'tagging_topic_id'
    ];

    public function image() {
        return $this->hasMany(TaggingImage::class);
    }
}
