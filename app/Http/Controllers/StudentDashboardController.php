<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\Student, Brightfox\Models\Exam, Brightfox\Models\ExamSectionMetadata;

class StudentDashboardController extends Controller
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
    public function index(Request $request)
    {
        $student = Student::where('user_id', Auth::id())->first();
        $exams = Exam::all();
        $allExams = Exam::all();
        $studentExams = $student->exams;
        $examTypes = $exams->unique('type')->pluck('type')->all();
        $typeSelected = '';

        if($request->has('examType')) {
            $exams = $exams->where('type', $request->input('examType'));
            $studentExams = $studentExams->filter(function ($studentExam) use($request) {
               return $studentExam->exam->type === $request->input('examType');
            });
            $typeSelected = $request->input('examType');
        }

        return view('student_dashboard', [
            'exams' => $exams,
            'student' => $student,
            'studentExams' => $studentExams,
            'allSections' => ExamSectionMetadata::all(),
            'examTypes' => $examTypes,
            'typeSelected' => $typeSelected,
            'allExams' => $allExams,
        ]);
    }
}
