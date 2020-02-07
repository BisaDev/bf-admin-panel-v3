<?php

namespace Brightfox\Http\Transformers;

class TaggingQuestionTransformer extends Transformer
{
    public function transform($question)
    {
        $images = $question->image;
        $imageTransformer = new TaggingImageTransformer;
        $images = $imageTransformer->transformCollection($images);

        return [
            'id' => $question->id,
            'tagging_subject_id' => $question->tagging_subject_id,
            'tagging_topic_id' => $question->tagging_topic_id,
            'image' => $images
        ];
    }
}