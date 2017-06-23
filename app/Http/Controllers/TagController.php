<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Tag;
use Illuminate\Http\Request;
use DB;

class TagController extends Controller
{
    public function repository(Request $request)
    {
        return response()->json(DB::table('tags')->select('name')->where('name', 'like', '%'.$request->input('query').'%')->get()->pluck('name'));
    }
}