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

    public function create ()
    {
        return view('tagging_subject.create');
    }

    public function store()
    {
        $subjects = new TaggingSubject();

        $subjects->name = request('subject_name');
        $subjects->save();

        return redirect('/taggingsubject');

    }

}
