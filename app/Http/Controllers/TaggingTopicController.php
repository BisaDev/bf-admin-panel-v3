<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject, Brightfox\TaggingTopic;
use Illuminate\Http\Request;

class TaggingTopicController extends Controller
{
    public function create($subject_id)
    {
        return view('tagging_topic.create' , compact('subject_id'));
    }

    public function store(Request $request)
    {
        $subject = TaggingTopic::create($request->only(['name' , 'tagging_subject_id']));

        return redirect(route('taggingsubjects'));
    }
}
