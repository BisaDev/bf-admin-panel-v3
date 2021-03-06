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

    public function question($topic_id)
    {
        $questions = TaggingQuestion::where("tagging_topic_id", $topic_id)->with('image')->get();

        $questions = $this->transformer->transformCollection($questions);

        return $questions;
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
                if ($currentQuestion) {
                    $currentQuestion->pdf_id = $Num;
                    $currentQuestion->save();
                }

                foreach ($question->image as $image) {
                    $questionName = $image->image_url;
                    $questionImg = $image->questionFileUrl;

                    $questionRoute = "/$source/pdf$Num/$questionName";

                    $zip->addFile(public_path($questionImg), $questionRoute);

                    if($image->explanation_url){
                        $explanationName = $image->explanation_url;
                        $explanationImg = $image->explanationFileUrl;

                        $explanationRoute = "/$source/pdf$Num/$explanationName";

                        $zip->addFile(public_path($explanationImg), $explanationRoute);
                    }
                }
            }
        }

        $zip->close();
        return response()->download($zip_file);
    }

}
