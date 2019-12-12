<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;

class TaggingToolController extends Controller
{
    function index () {
        return view('tagging_tool.tt_index');

    }
}
