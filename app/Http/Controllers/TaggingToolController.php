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

    public function set_topic(Request $request) {
        $topic_id = $request->topic_id;
        $question_id = $request->question_id;

        $question = TaggingQuestion::find($request->question_id);
        $question->tagging_topic_id = $request->topic_id;
        $question->save();
        return $request;
    }

    public function questions_list($subject_id) {
        //to do: add whereNull filter
        $questions =  TaggingQuestion::where('tagging_subject_id',$subject_id)->with('image');

        $questions = $questions->get();

        return $questions->toJson();
    }

}
