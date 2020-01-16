<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\User;
use Illuminate\Http\Request;
use Brightfox\TaggingSubject;
use Illuminate\Support\Facades\DB;

class TaggingToolController extends Controller
{
    public function index() {
        $instructors = User::role('instructor')->get();
        $subjects = TaggingSubject::all();

        return view('tagging_tool.index', compact('instructors', 'subjects'));
    }

    public function tag($subject_id) {
        return view('tagging_tool.tag', compact('subject_id'));
        return $subject_id;
    }

    public function questions($id) {
        $images =  DB::table('tagging_questions')->where('tagging_subject_id', '=',$id)->get();
        return $images->toJson();
    }
}
