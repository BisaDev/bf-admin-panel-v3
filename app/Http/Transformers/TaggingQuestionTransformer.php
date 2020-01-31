<?php

namespace Brightfox\Http\Transformers;

use Illuminate\Support\Facades\Storage;

class TaggingQuestionTransformer extends Transformer
{
    public function transform($image)
    {
        $filePath = $image->getImagePath();
        $questionFile = $image->image_url;
        $explanationFile = $image->explanation_url;
        $questionFileUrl = Storage::url($filePath . $questionFile);
        $explanationFileUrl = Storage::url($filePath . $explanationFile);

        return [
            'id'                  => $image->id,
            'image_answer'        => $image->image_answer,
            'image_url'           => $image->image_url,
            'questionFileUrl'     => $questionFileUrl,
            'explanation_url'     => $image->explanation_url,
            'explanationFileUrl'  => $explanationFileUrl,
            'tagging_question_id' => $image->tagging_question_id,
        ];

    }
}