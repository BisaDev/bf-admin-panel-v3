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

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
