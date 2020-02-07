<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Http\Transformers\TaggingQuestionTransformer;
use Brightfox\TaggingQuestion;
use Brightfox\TaggingTopic;
use Illuminate\Http\Request;

class TaggingQuestionController extends Controller
{
    private $transformer;

    public function __construct(TaggingQuestionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function questions_list($subject_id) {
        $questions =  TaggingQuestion::where('tagging_subject_id',$subject_id)
            ->with('image')->whereNull('tagging_topic_id')->get();

        $questions = $this->transformer->transformCollection($questions);

        return $questions;


    }
}
