<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Brightfox\TaggingQuestion;
use Brightfox\Http\Transformers\TaggingQuestionTransformer;

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

    public function download(Request $request) {


        $questionImg = $request->imageFile;
        $explanationImg = $request->explanationFile;
        $dude = 'storage/IPN - Physics-answer2-questionImage_0.png';

        $file= public_path()."/download/info.pdf";


        return response()->download(public_path($questionImg));
    }

}
