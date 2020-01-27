<?php

namespace Brightfox;

use Brightfox\Models\User;
use Illuminate\Database\Eloquent\Model;

class TaggingLog extends Model
{
    protected $fillable = [
        'user_id', 'tagging_question_id'
    ];

    public function question () {
        return $this->hasOne(TaggingQuestion::class);
    }

    public function user () {
        return $this->hasOne(User::class);
    }
}
