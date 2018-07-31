<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\StudentExam, Brightfox\Models\StudentExamSection, Brightfox\Models\Student, Brightfox\Models\Exam, Brightfox\Models\ExamAnswer, Brightfox\Models\ExamSection;

class AnswerSheetController extends Controller
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

        $sectionCollection->each(function ($section) use ($studentExam) {
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
        $studentExamSectionCollection = collect($studentExam->sections);

        $studentExamSection = StudentExamSection::where('student_exam_id', $studentExam->id)
            ->where('section_number', $section)
            ->first();

        $allSections = $studentExamSectionCollection->pluck('section_number');
        $lastSection = $studentExamSectionCollection->last()->section_number;
        $questions = $this->sections[$section]['questions'];

        $numberCorrectSection = 0;

        for ($i = 1; $i <= $questions; $i++) {
            $examAnswer = ExamAnswer::create([
                'student_exam_section_id' => $studentExamSection->id,
                'question_number' => $i,
                'answer' => $request->input('question_' . $i),
                'guessed' => $request->input('guessed_' . $i)
            ]);
            $correct = $examAnswer->correctAnswer;
            $understood = 0;
            if (($correct->correct_1 === $examAnswer->answer || $correct->correct_2 === $examAnswer->answer || $correct->correct_3 === $examAnswer->answer || $correct->correct_4 === $examAnswer->answer || $correct->correct_5 === $examAnswer->answer)) {
                $numberCorrectSection = $numberCorrectSection + 1;
                $understood = 1;
            }
            $examAnswer->understood = $understood;
            $examAnswer->save();
        }

        $studentExamSection->number_correct = $numberCorrectSection;
        $studentExamSection->save();

        if ($lastSection == $section) {
            $studentExam = StudentExam::find($studentExam->id);
            $totalCorrect = collect($studentExam->sections->pluck('number_correct')->sum());
            $studentExam->number_correct = $totalCorrect->first();
            $studentExam->save();

            return redirect(route('answer_sheet.show_results', $studentExam->id));
        } else {
            $nextSection = $allSections->get($allSections->search($section) + 1);
            return redirect(route('answer_sheet.show_answer_sheet', $nextSection));
        }
    }

    public function show_results($studentExamId)
    {
        $studentExam = StudentExam::find($studentExamId);

        foreach ($studentExam->sections as $examSection) {
            foreach ($examSection->questions as $question) {
                $correct = $question->correctAnswer;
                $answers[] = [
                    'section' => $examSection->section_number,
                    'answer' => $question->answer,
                    'correct' => [$correct->correct_1, $correct->correct_2, $correct->correct_3, $correct->correct_4, $correct->correct_5],
                    'topic' => $correct->topic,
                ];
            }
        }

        $topics = collect($answers)->groupBy('topic')->toArray();

        foreach ($topics as $topicName => $topic) {
            $score = 0;
            $numberOfQuestions = count($topic);

            foreach ($topic as $question) {
                if ($question['answer'] === $question['correct'][0] || $question['answer'] === $question['correct'][1] || $question['answer'] === $question['correct'][2] || $question['answer'] === $question['correct'][3] || $question['answer'] === $question['correct'][4]) {
                    $score++;
                }
            }
            $scoreByTopic[] = [
                'section' => $topic[0]['section'],
                'score' => round(($score/$numberOfQuestions)*100),
                'right' => $score,
                'wrong' => $numberOfQuestions - $score,
                'topic' => $topicName
            ];
        }

        return view('students_web.show_results', [
            'item' => StudentExam::find($studentExamId),
            'sectionData' => $this->sections,
            'topics' => $scoreByTopic
        ]);
    }

    public function edit_understood(Request $request, $studentExamSectionId)
    {
        $studentExamSection = StudentExamSection::where('id', $studentExamSectionId)->first();
        $understoodQuestions = collect(array_slice($request->all(), '1'));

        $understoodQuestions->keys()->each(function ($questionNumber) use ($studentExamSection){
            $questionNumber = substr($questionNumber, 13);
            $question = collect($studentExamSection->questions)->where('question_number', $questionNumber);
            $question->first()->understood = 1;
            $question->first()->save();
        });

        return redirect(route('answer_sheet.show_results', $studentExamSection->studentExam->id));
    }
}