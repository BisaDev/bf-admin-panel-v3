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


        foreach($request->all() as $key => $value) {
            if(strpos($key, "_") != false){
                $keys = explode("_", $key);
                $images[$keys[1]][$keys[0]] = $value;
            }
            if (strstr($key, "questionImage") && $value != "null") {
                Storage::put("/tt_images/", $value);
            }
            if (strstr($key, "explanationImg") && $value != "null") {
                Storage::put("/tt_images/", $value);
            }

        }


        /* Do everything */
        dd($images);

    }

}
