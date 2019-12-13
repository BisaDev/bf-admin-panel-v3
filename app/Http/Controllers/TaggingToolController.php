<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;

class TaggingToolController extends Controller
{
    public function index() {
        return view('tagging_tool.index');
    }
}
