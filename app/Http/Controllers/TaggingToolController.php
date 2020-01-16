<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\User;
use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Brightfox\TaggingQuestion;

class TaggingToolController extends Controller
{
    public function index() {
        $instructors = User::role('instructor')->get();
        $subjects = TaggingSubject::all();

        return view('tagging_tool.index', compact('instructors', 'subjects'));
    }

    public function tag($subject_id) {
        $subject = TaggingSubject::find($subject_id);

        return view('tagging_tool.tag', compact('subject'));

    }

    public function questions($subject_id) {
        $images =  TaggingQuestion::where('tagging_subject_id',$subject_id)->get();

        return $images->toJson();
    }
}
