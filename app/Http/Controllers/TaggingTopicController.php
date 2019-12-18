<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingSubject, Brightfox\TaggingTopic;

class TaggingTopicController extends Controller
{
    public function create($subject_id)
    {
        $subject = TaggingSubject::find($subject_id);
        
        return view('tagging_topic.create' , compact('subject'));
    }

    public function store()
    {
        $subject = TaggingTopic::create($request->only(['name' , 'tagging_subject_id']));

        return redirect('/taggingtopics');
    }
}
