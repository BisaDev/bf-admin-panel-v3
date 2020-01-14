<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingImage;
use Brightfox\TaggingTopic;
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
        $subject = $request->subject;


        foreach($request->all() as $key => $value) {
            if(strpos($key, "_") != false){
                $keys = explode("_", $key);
                $images[$keys[1]][$keys[0]] = $value;
            }
            if (strstr($key, "answer") && $value != "null") {
                $answer = $value;
            }
            if (strstr($key, "questionImage") && $value != "null") {
                $extension = $value->getClientOriginalExtension();
                Storage::put("tt_images/$subject-$answer-$key.$extension", file_get_contents($value));
            }
            if (strstr($key, "explanationImg") && $value != "null") {
                $extension = $value->getClientOriginalExtension();
                Storage::put("tt_images/$subject-$answer-$key.$extension", file_get_contents($value));
            }
        }

        /* Do everything */
        foreach($images as $image) {
            TaggingImage::create(['image_answer' => $image['answer'], 'image_url' => 'https://',
                'tagging_question_id' => $request->subjectID, 'explanation_url' => 'http://']);
        }
    }

}
