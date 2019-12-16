<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;

class TaggingTopicController extends Controller
{
    public function index () {
        return view('tagging_topic.index');
    }

    public function create () {
        return view('tagging_topic.create');
    }
 }
