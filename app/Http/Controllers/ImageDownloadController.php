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

    public function question($topic_id)
    {
        $question = TaggingQuestion::where("tagging_topic_id", $topic_id)->with('image')->get();

        $question = $this->transformer->transformCollection($question);

        return $question;
    }

    public function download(Request $request)
    {

        $zip_file = 'images.zip';
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $source = $request->source;
        $zip_data = json_decode($request->payload);

        foreach ($zip_data as $pdf) {
           foreach ($pdf as $question) {
               $Num = $question->pdf_id;

               $currentQuestion = TaggingQuestion::find($question->id);
               if($currentQuestion) {
                   $currentQuestion->pdf_id = $Num;
                   $currentQuestion->save();
               }

               $questionName = $question->image->image_url;
               $questionImg= $question->imageFile;

               $questionRoute= "/$source/pdf$Num/$questionName";

               $explanationName = $question->image->explanation_url;
               $explanationImg = $question->explanationFile;

               $explanationRoute= "/$source/pdf$Num/$explanationName";

               $zip->addFile(public_path($explanationImg) , $explanationRoute);
               $zip->addFile(public_path($questionImg), $questionRoute);
           }
        }
        $zip->close();

        return response()->download($zip_file);
    }

}
