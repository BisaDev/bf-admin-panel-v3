<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\StudentExam, Brightfox\Models\StudentExamSection, Brightfox\Models\Student, Brightfox\Models\Exam, Brightfox\Models\ExamAnswer, Brightfox\Models\ExamSection;

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

    public function create_exam(Request $request)
    {
        $user_id = Auth::id();
        $student = Student::where('user_id', $user_id)->first();
        $exam = Exam::where('test_id', $request->input('test-id'))->first();

        $studentExam = StudentExam::create([
            'exam_id' => $exam->id,
            'student_id' => $student->id
        ]);

        $sectionCollection = collect($request->input('sections'));

        $sectionCollection->each(function($section) use($studentExam){
            StudentExamSection::create([
                'student_exam_id' => $studentExam->id,
                'section_number' => $section
            ]);
        });

        $request->session()->put('studentExam', $studentExam);
        return redirect(route('answer_sheet.show_answer_sheet', $sectionCollection->first()));
    }

    public function show_answer_sheet($section)
    {
        return view('students_web.student_answer_sheet_' . $section, compact('section'));
    }

    public function save_answers(Request $request)
    {
        $section = $request->section;
        $studentExam = $request->session()->get('studentExam');
        $studentExamSectionCollection = collect(StudentExamSection::where('student_exam_id', $studentExam->id)->get());

        $studentExamSection = StudentExamSection::where('student_exam_id', $studentExam->id)
            ->where('section_number', $section)
            ->first();

        $allSections = $studentExamSectionCollection->map(function($studentExamSection){
            return $studentExamSection->section_number;
        });

        $lastSection = $studentExamSectionCollection->last()->section_number;

        switch($section){
            case 1:
                $questions = 52;
                break;
            case 2:
                $questions = 44;
                break;
            case 3:
                $questions = 20;
                break;
            case 4:
                $questions = 38;
                break;
        }

        $numberCorrectSection = 0;

        for($i = 1; $i <= $questions; $i++){
            $examAnswer = ExamAnswer::create([
                'student_exam_section_id' => $studentExamSection->id,
                'question_number' => $i,
                'answer' => $request->input('question_'.$i),
                'guessed' => $request->input('guessed_'.$i)
            ]);
            $correct = ExamSection::where('exam_id', $studentExam->exam_id)
                ->where('section_number', $section)
                ->where('question_number', $i)
                ->first();
            if (($correct->correct_1  === $examAnswer->answer || $correct->correct_2 === $examAnswer->answer || $correct->correct_3 === $examAnswer->answer || $correct->correct_4 === $examAnswer->answer || $correct->correct_5 === $examAnswer->answer)) {
                $numberCorrectSection = $numberCorrectSection + 1;
            }
        }

        $studentExamSection->number_correct = $numberCorrectSection;
        $studentExamSection->save();

        if($lastSection == $section){
            $totalCorrect = collect($studentExamSectionCollection->pluck('number_correct'))->sum();
            $totalCorrect = $totalCorrect + $numberCorrectSection;
            $studentExam->number_correct = $totalCorrect;
            $studentExam->save();

            return redirect(route('student_dashboard'));
        } else {
            $nextSection = $allSections->get($allSections->search($section) + 1);
            return redirect(route('answer_sheet.show_answer_sheet', $nextSection));
        }

    }
}
