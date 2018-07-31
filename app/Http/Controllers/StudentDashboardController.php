<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\Student, Brightfox\Models\StudentExamSection ;

class StudentDashboardController extends Controller
{
    protected $sections = StudentExamSection::SECTIONS;
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
    public function index()
    {
        $user_id = Auth::id();
        $student = Student::where('user_id', $user_id)->first();
        return view('student_dashboard', [
            'student' => $student,
            'allSections' => $this->sections,
        ]);
    }
}
