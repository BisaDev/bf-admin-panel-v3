<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingImage extends Model
{
    protected $fillable = [
        'image_answer', 'image_url', 'tagging_question_id', 'explanation_url'
    ];

    public function question () {
        return $this->hasOne(TaggingQuestion::class);
    }
}
