<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\TaggingSubject;

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
        }

        /* Do everything */
        dd($images);
    }

}
