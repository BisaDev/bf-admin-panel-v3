<?php

namespace Brightfox\Http\Transformers;



class TaggingSubjectTransformer extends Transformer {
    public function transform ($subject) {
        $taggedQuestions = 0;
        $untaggedQuestions = 0;
        foreach ($subject['questions'] as $key ) {
            if(is_null($key->tagging_topic_id)) {
                $untaggedQuestions++;
            } else {
                $taggedQuestions++;
            }
        }

        return [
            'id' => (int)$subject->id,
            'name' => $subject->name,
            'tagged_questions_count' => $taggedQuestions,
            'untagged_questions_count' => $untaggedQuestions,
        ];
    }
}
