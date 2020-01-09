<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\User;
use Brightfox\TaggingSubject;
use Illuminate\Http\Request;

class TaggingToolController extends Controller
{
    public function index() {
        $instructors = User::role('instructor')->get();
        $subjects = TaggingSubject::all();

        return view('tagging_tool.index', compact('instructors', 'subjects'));
    }
}
