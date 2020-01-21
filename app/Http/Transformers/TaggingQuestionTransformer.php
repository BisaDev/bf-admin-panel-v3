<?php

namespace Brightfox\Http\Transformers;

use Illuminate\Support\Facades\Storage;

class TaggingQuestionTransformer {
    public function transform($questions) {

        foreach ($questions as $key => $value) {
            $filePath = $value->image->getImagePath();
            $fileName = $value->image->image_url;
            $fileLocation = Storage::url($filePath.$fileName);
            $value->image->image_url = $fileLocation;
        }
        return $questions;

    }
}