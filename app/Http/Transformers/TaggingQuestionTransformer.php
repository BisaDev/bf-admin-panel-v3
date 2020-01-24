<?php

namespace Brightfox\Http\Transformers;

use Illuminate\Support\Facades\Storage;

class TaggingQuestionTransformer {
    public function transform($questions) {

        foreach ($questions as $key => $value) {
            $filePath = $value->image->getImagePath();
            $questionFile = $value->image->image_url;
            $explanationFile = $value->image->explanation_url;
            $questionFileUrl = Storage::url($filePath.$questionFile);
            $explanationFileUrl = Storage::url($filePath.$explanationFile);
            $value->image->imageFile = $questionFileUrl;
            $value->image->explanationFile = $explanationFileUrl;
        }
        return $questions;

    }
}