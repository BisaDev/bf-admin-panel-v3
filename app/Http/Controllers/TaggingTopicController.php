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
        return view('tagging_topic.index');

    }
}
