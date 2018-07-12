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
        $sections = collect($request->all())->filter(function ($section) {
            return is_numeric($section);
        });

        return view('students_web.student_answer_sheet_' . $sections->first(), compact('sections'));
    }

    public function answer_sheet_1()
    {
        return view('students_web.student_answer_sheet_1');
    }

    public function answer_sheet_2()
    {
        return view('students_web.student_answer_sheet_2');
    }

    public function answer_sheet_3()
    {
        return view('students_web.student_answer_sheet_3');
    }

    public function answer_sheet_4()
    {
        return view('students_web.student_answer_sheet_4');
    }
}