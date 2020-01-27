<?php

namespace Brightfox\Http\Transformers;



class TaggingSubjectTransformer {
    public function transform ($subject) {
        foreach ($subject as $item) {
            $taggedQuestions = 0;
            $untaggedQuestions = 0;
            foreach ($item['questions'] as $key ) {
                if(is_null($key->tagging_topic_id)) {
                    $untaggedQuestions++;
                } else {
                    $taggedQuestions++;
                }
            }
            $item->taggedQuestions = $taggedQuestions;
            $item->untaggedQuestions = $untaggedQuestions;
        }

        return $subject;
    }


}