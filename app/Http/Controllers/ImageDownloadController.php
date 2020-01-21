<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject;
use Illuminate\Http\Request;

class ImageDownloadController extends Controller
{

    public function index()
    {
        $subjects = TaggingSubject::with('topics')->get();
        return view('image_download.index', compact('subjects'));
    }

}
