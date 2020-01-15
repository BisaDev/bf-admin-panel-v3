<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingImage;
use Brightfox\TaggingQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{

    public function index()
    {
        return view('image_upload.index');
    }

    public function upload (Request $request)
    {
        $images = [];
        $answer = "";
        $path= "tt_images/";
        $subject = $request->subject;

        foreach($request->all() as $key => $value) {
            if(strpos($key, "_") != false){
                $keys = explode("_", $key);
                $images[$keys[1]][$keys[0]] = $value;

                if (strstr($key, "questionImage") && $value != "null") {
                    $extension = $value->getClientOriginalExtension();
                    $fileName= "$subject-$answer-$key.$extension";

                    Storage::put("$path$fileName", file_get_contents($value));
                    $images[$keys[1]]["questionImgUrl"] = $fileName;
                }
                if (strstr($key, "explanationImg") && $value != "null") {
                    $extension = $value->getClientOriginalExtension();
                    $fileName= "$subject-$answer-$key.$extension";

                    Storage::put("$path$fileName", file_get_contents($value));
                    $images[$keys[1]]["explanationImgUrl"] = $fileName;
                }
            }
            if (strstr($key, "answer") && $value != "null") {
                $answer = $value;
            }
        }

        $taggingQuestion = TaggingQuestion::create(["tagging_subject_id" => $request->subjectID]);

        foreach($images as $image) {
            TaggingImage::create(['image_answer' => $image['answer'], 'image_url' => $image['questionImgUrl'],
                'tagging_question_id' => $taggingQuestion->id, 'explanation_url' => $image['questionImgUrl']]);
        }
    }

}
