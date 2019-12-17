<?php

namespace Brightfox\Http\Controllers;

use Brightfox\TaggingTopic;

class TaggingTopicController extends Controller
{
    public function index()
    {
        $topics = TaggingTopic::all();

        return view('tagging_topic.index', compact('topics'));
    }

    public function create()
    {
        return view('tagging_topic.create');
    }

    public function store()
    {
        $topics = new TaggingTopic();

        $topics->name = request('topic_name');
        $topics->tagging_subject_id = request('tagging_subject_id');
        $topics->save();

        return redirect('/taggingtopics');
    }
}
