<?php

namespace Brightfox\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\StudentExam, Brightfox\Models\StudentExamSection, Brightfox\Models\Student, Brightfox\Models\Exam, Brightfox\Models\ExamAnswer, Brightfox\Models\User, Brightfox\Models\ExamScoreTable;

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
        $studentExam = $student->exams->whereIn('exam_id', $exam->id);

        if($studentExam->isEmpty()) {
            $studentExam = StudentExam::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id
            ]);
        } else {
            $studentExam = $studentExam->first();
        }

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

    public function show_answer_sheet($section, Request $request)
    {
        if ($request->session()->has('studentExam')) {
            return view('students_web.student_answer_sheet_' . $section, compact('section'));
        } else {
            return redirect()->back();
        }
    }

    public function save_answers(Request $request)
    {
        $section = $request->section;
        $studentExam = $request->session()->get('studentExam');
        $studentExamSectionCollection = collect($studentExam->sections);
        $scoreTable = collect(json_decode($studentExam->exam->scoreTable->score_table, true));

        $studentExamSection = StudentExamSection::where('student_exam_id', $studentExam->id)
            ->where('section_number', $section)
            ->get();

        $studentExamSection = $studentExamSection->last();
        $allSections = $studentExamSectionCollection->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('s');
        });
        $allSections = $allSections->last()->pluck('section_number');

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
            $understood = 0;
            if ($examAnswer->AnswerResult) {
                $numberCorrectSection = $numberCorrectSection + 1;
                if ($examAnswer->guessed != 1) {
                    $understood = 1;
                }
            }
            $examAnswer->understood = $understood;
            $examAnswer->save();
        }

        $studentExamSection->time = $request->input('time') + 1;
        $studentExamSection->number_correct = $numberCorrectSection;
        
        $score = $scoreTable->get($numberCorrectSection)[$this->sections[$section]['tableScore']];
        $studentExamSection->score = ($section == 1 || $section == 2) ? $score*10 : $score;

        $studentExamSection->save();

        if ($lastSection == $section) {
            $studentExam = StudentExam::find($studentExam->id);
            $firstSections = collect($studentExam->sections->unique('section_number'));

            $totalCorrect = $firstSections->pluck('number_correct')->sum();
            $totalTime = $firstSections->pluck('time')->sum();

            $studentExam->number_correct = $totalCorrect;
            $studentExam->time = $totalTime;

            if($firstSections->count() == 4) {
                $spanishScore =  $firstSections->whereIn('section_number', [1, 2])->pluck('score')->sum();
                $mathRawScore = $firstSections->whereIn('section_number', [3, 4])->pluck('number_correct')->sum();
                $mathScore = $scoreTable->get($mathRawScore)['Math Section Score'];
                $totalScore = $mathScore + $spanishScore;
                $studentExam->score = $totalScore;
            }

            $studentExam->save();
            $request->session()->forget('studentExam');

            return redirect(route('answer_sheet.show_results', $studentExam->id));
        } else {
            $nextSection = $allSections->get($allSections->search($section) + 1);
            return redirect(route('answer_sheet.show_answer_sheet', $nextSection));
        }
    }

    public function show_results($studentExamId)
    {
        $studentExam = StudentExam::find($studentExamId);
        $user = Auth::user();

        if ($user->can('view', $studentExam)) {
            foreach ($studentExam->sections as $examSection) {

                if(!$examSection->time) {
                    $examSection->delete();
                } else {
                    foreach ($examSection->questions as $question) {
                        $correctAnswer = $question->correctAnswer;
                        $isCorrect = $question->AnswerResult;
                        $answers[] = [
                            'id' => $examSection->id,
                            'section' => $examSection->section_number,
                            'answer' => $question->answer,
                            'isCorrect' => $isCorrect,
                            'topic' => $correctAnswer->topic,
                        ];
                    }
                }
            }

            $sections = collect($answers)->groupBy('id')->toArray();

            foreach ($sections as $section) {
                $topics = collect($section)->groupBy('topic')->toArray();
                foreach ($topics as $topicName => $topic) {
                    $score = 0;
                    $numberOfQuestions = count($topic);

                    foreach ($topic as $question) {
                        if ($question['isCorrect']) {
                            $score++;
                        }
                    }
                    $scoreByTopic[] = [
                        'section' => $topic[0]['section'],
                        'score' => round(($score / $numberOfQuestions) * 100),
                        'right' => $score,
                        'wrong' => $numberOfQuestions - $score,
                        'topic' => $topicName,
                        'id' => $section[0]['id'],
                    ];
                }
            }

            $scoreByTopic = collect($scoreByTopic)->sortByDesc('score')->groupBy('id')->toArray();

            return view('students_web.show_results', [
                'item' => StudentExam::find($studentExamId),
                'sectionData' => $this->sections,
                'topics' => $scoreByTopic
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function edit_understood(Request $request)
    {
        $studentExamSectionId = $request->input('section');
        $questionNumber = $request->input('question');

         ExamAnswer::where('student_exam_section_id', $studentExamSectionId)
            ->where('question_number', $questionNumber)
            ->update(['understood' => 1]);

        return response()->json(1);
    }
}
