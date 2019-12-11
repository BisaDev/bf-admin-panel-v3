<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class Tagging_Topic extends Model
{
    protected $table = 'tagging_topics';

    public function subject () {
        return $this->belongsTo('App\Tagging_Subject');
    }
}
