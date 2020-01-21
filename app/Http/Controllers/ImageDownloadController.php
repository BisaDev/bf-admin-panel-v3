<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Http\Transformers\TaggingQuestionTransformer;
use Brightfox\TaggingImage;
use Brightfox\TaggingQuestion;
use Brightfox\TaggingSubject;
use Illuminate\Http\Request;

class ImageDownloadController extends Controller
{
    private $transformer;

    public function __construct(TaggingQuestionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index()
    {
        $subjects = TaggingSubject::with('topics')->get();
        return view('image_download.index', compact('subjects'));
    }

    public function question($topic_id){

        $question = TaggingQuestion::where("tagging_topic_id", $topic_id)->with('image')->get();

        $question = $this->transformer->transform($question);

        return $question->toJson();
    }

}
