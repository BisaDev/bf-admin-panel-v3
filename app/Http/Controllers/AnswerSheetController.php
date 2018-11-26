<?php

namespace Brightfox\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Models\StudentExam, Brightfox\Models\StudentExamSection, Brightfox\Models\Student, Brightfox\Models\Exam, Brightfox\Models\ExamAnswer, Brightfox\Models\User, Brightfox\Models\ExamSectionMetadata;

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

        if ($exam->IsMiniExam) {
            StudentExamSection::create([
                'student_exam_id' => $studentExam->id,
                'section_number' => 1
            ]);
        } else {
            $sectionCollection = collect($request->input('sections'));

            $sectionCollection->each(function ($section) use ($studentExam) {
                StudentExamSection::create([
                    'student_exam_id' => $studentExam->id,
                    'section_number' => $section
                ]);
            });
        }

        $studentExamSectionId = $studentExam->sections->where('time', NULL)->first()->id;

        $request->session()->put('studentExam', $studentExam);
        return redirect(route('answer_sheet.show_answer_sheet', $studentExamSectionId));
    }

    public function show_answer_sheet($studentExamSectionID, Request $request)
    {
        if ($request->session()->has('studentExam')) {
            $studentExamSection = StudentExamSection::find($studentExamSectionID);
            $exam = Exam::find($studentExamSection->studentExam->exam_id);
            return view('students_web.student_answer_sheet', [
                'exam' => $exam,
                'studentExamSection' => $studentExamSection,
                'examMetadata' => $studentExamSection->metadata,
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function save_answers(Request $request)
    {
        $studentExamSection = StudentExamSection::find($request->examSectionID);
        $section = $studentExamSection->section_number;
        $studentExam = $studentExamSection->studentExam;
        $studentExamSectionCollection = collect($studentExam->sections);
        $examType = $studentExam->exam->type;

        $lastSection = $studentExamSectionCollection->last()->section_number;
        if ($studentExam->exam->IsMiniExam) {
            $questions = $studentExam->exam->mini_exam_questions;
        } else {
            $questions = $studentExamSection->metadata->questions;
        }

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
        $studentExamSection->save();

        if ($examType === 'SAT') {
            $scoreTable = collect(json_decode($studentExam->exam->scoreTable->score_table, true));
            if ($section == 1 || $section == 2) {
                $score = $scoreTable->get($numberCorrectSection)[$studentExamSection->metadata->table_score];
                $studentExamSection->score = $score * 10;
            } else {
                $studentExamUpdated = StudentExam::find($studentExam->id);
                $uncompletedMathSections = $studentExamUpdated->sections->where('math_completed', 0);

                if ($section == 3) {
                    $sectionFour = $uncompletedMathSections->where('section_number', 4);
                    if ($sectionFour->isEmpty()) {
                        $studentExamSection->score = 0;
                        $studentExamSection->math_completed = 0;
                    } else {
                        $sectionFour = $sectionFour->first();
                        if (!$sectionFour->number_correct) {
                            $studentExamSection->score = 0;
                            $studentExamSection->math_completed = 0;
                        } else {
                            $score = $sectionFour->number_correct + $numberCorrectSection;
                            $score = $scoreTable->get($score)[$studentExamSection->metadata->table_score];
                            $studentExamSection->score = $score;
                            $studentExamSection->math_completed = 1;
                            $sectionFour->score = $score;
                            $sectionFour->math_completed = 1;
                            $sectionFour->save();
                        }
                    }
                } else {
                    $sectionThree = $uncompletedMathSections->where('section_number', 3);
                    if ($sectionThree->isEmpty()) {
                        $studentExamSection->score = 0;
                        $studentExamSection->math_completed = 0;
                    } else {
                        $sectionThree = $sectionThree->first();
                        if (!$sectionThree->number_correct) {
                            $studentExamSection->score = 0;
                            $studentExamSection->math_completed = 0;
                        } else {
                            $score = $sectionThree->number_correct + $numberCorrectSection;
                            $score = $scoreTable->get($score)[$studentExamSection->metadata->table_score];
                            $studentExamSection->score = $score;
                            $studentExamSection->math_completed = 1;
                            $sectionThree->score = $score;
                            $sectionThree->math_completed = 1;
                            $sectionThree->save();
                        }
                    }
                }
            }
        } else if ($examType === 'ACT') {
            $scoreTable = collect(json_decode($studentExam->exam->scoreTable->score_table, true));
            $score = $scoreTable->get($numberCorrectSection - 1)[$studentExamSection->metadata->table_score];
            $studentExamSection->score = $score;
        } else if ($studentExam->exam->IsMiniExam) {
            $studentExamSection->score = $numberCorrectSection;
        }

        $studentExamSection->save();

        if ($lastSection == $section) {
            $studentExam = StudentExam::find($studentExam->id);
            $firstSections = collect($studentExam->sections->unique('section_number'));

            $totalCorrect = $firstSections->pluck('number_correct')->sum();
            $totalTime = $firstSections->pluck('time')->sum();

            $studentExam->number_correct = $totalCorrect;
            $studentExam->time = $totalTime;

            if ($studentExam->exam->IsMiniExam) {
                $studentExam->score = $totalCorrect;
            }

            if ($firstSections->count() == 4) {
                if ($examType === 'SAT') {
                    $spanishScore =  $firstSections->whereIn('section_number', [1, 2])->pluck('score')->sum();
                    $mathScore = $firstSections->where('section_number', 3)->pluck('score')->sum();
                    $totalScore = $mathScore + $spanishScore;
                    $studentExam->score = $totalScore;
                } else {
                    $sumOfScores = $firstSections->pluck('score')->sum();
                    $studentExam->score = $sumOfScores / 4;
                }
            }

            $studentExam->save();
            $request->session()->forget('studentExam');

            return redirect(route('answer_sheet.show_results', $studentExam->id));
        } else {
            $nextSection = StudentExam::find($studentExam->id)->sections->where('time', NULL)->first()->id;;
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
                'studentExam' => StudentExam::find($studentExamId),
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
        $understood = $request->input('understood');

        ExamAnswer::where('student_exam_section_id', $studentExamSectionId)
            ->where('question_number', $questionNumber)
             ->update(['understood' => $understood]);

        return response()->json($understood);
    }
}
