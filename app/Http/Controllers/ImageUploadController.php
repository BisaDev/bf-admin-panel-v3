<?php

namespace Brightfox\Http\Controllers;

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
                Storage::put("tt_images/$subject-$answer-key.jpg", file_get_contents($value));
            }
            if (strstr($key, "explanationImg") && $value != "null") {
                Storage::put("tt_images/$subject-$answer-key.jpg", file_get_contents($value));
            }

        }


        /* Do everything */
        dd($images);

    }

}
