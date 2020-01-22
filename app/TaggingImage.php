<?php

namespace Brightfox;

use Illuminate\Database\Eloquent\Model;

class TaggingImage extends Model
{
    protected $fillable = [
        'image_answer', 'image_url', 'tagging_question_id', 'explanation_url'
    ];

    private $imagePath = 'public/tt_images/';

    public function getImagePath () {
        return $this->imagePath;
    }

    public function question () {
        return $this->hasMany(TaggingQuestion::class);
    }
}
