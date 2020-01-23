<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Brightfox\TaggingQuestion;
use Brightfox\Http\Transformers\TaggingQuestionTransformer;
use function GuzzleHttp\Psr7\str;

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

        $zip_file = 'images.zip';

        return $request;

        $questionImg = $request->imageFile;
        $explanationImg = $request->explanationFile;

        $zip = new \ZipArchive();
        $zip->open($zip_file , \ZipArchive::CREATE );
        $zip->addFile(public_path($explanationImg) , $explanationImg);
        $zip->addFile(public_path($questionImg), $questionImg);
        $zip->close();

        return response()->download($zip_file);
    }

}
