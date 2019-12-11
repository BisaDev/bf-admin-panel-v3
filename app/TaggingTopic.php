<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingTopic extends Model
{
    protected $table = 'tagging_topics';

    public function subject () {
        return $this->belongsTo(Tagging_Subject::class);
    }
}
