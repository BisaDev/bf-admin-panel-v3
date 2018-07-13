<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;

class AnswerSheetController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $sections = collect($request->all())->filter(function ($section, $key) {
            return (is_numeric($section) || $key === 'sections');
        });
        return view('students_web.student_answer_sheet_' . $sections->first(), ['sections' => $sections]);
    }
}