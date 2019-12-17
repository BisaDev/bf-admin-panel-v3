<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject;

class TaggingSubjectController extends Controller
{
    public function index()
    {
        $subjects = TaggingSubject::all();

        return view('tagging_subject.index', compact('subjects'));
    }

    public function show () {
        return view('tagging_subject.create');
    }
 }
