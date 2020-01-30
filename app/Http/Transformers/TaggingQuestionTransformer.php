<?php

namespace Brightfox\Http\Transformers;

use Illuminate\Support\Facades\Storage;

class TaggingQuestionTransformer extends Transformer
{
    public function transform($question)
    {
        $filePath = $question->image->getImagePath();
        $questionFile = $question->image->image_url;
        $explanationFile = $question->image->explanation_url;
        $questionFileUrl = Storage::url($filePath . $questionFile);
        $explanationFileUrl = Storage::url($filePath . $explanationFile);

        return [
            'id' => $question->id,
            'image' => $question->image,
            'imageFile' => $questionFileUrl,
            'explanationFile' => $explanationFileUrl,
            'tagging_topic_id' => $question->tagging_topic_id,
            'tagging_subject_id' => $question->tagging_subject_id,
            'pdf_id' => $question->pdf_id,
        ];

    }
}