<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class Tagging_Subject extends Model
{
    protected $table = 'tagging_subjects';

    public function topic () {
        return $this->hasMany('App\Tagging_Topic');
    }
}
