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
        return $request;
    }

}
