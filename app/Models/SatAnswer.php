<?php

namespace Brightfox\Models;

use Illuminate\Database\Eloquent\Model;

class SatAnswer extends Model
{

    protected $fillable = [
        'text', 'is_correct', 'question_id'
    ];
}
