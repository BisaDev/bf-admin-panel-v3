<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Brightfox\Models\Exam, Brightfox\Models\ExamSection, Brightfox\Models\StudentExamSection, Brightfox\Models\StudentExam, Brightfox\Models\ExamScoreTable, Brightfox\Models\ExamAnswer;

class ExamPrepController extends Controller
{
    protected $sections = StudentExamSection::SECTIONS;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examList = Exam::paginate(20);
        return view('web.exam_prep.index', compact('examList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.exam_prep.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('csv')->store('/data');
        $csvStructureValidator = $this->validateStructureCsv(storage_path('app/' . $path));

        if ($csvStructureValidator->fails()) {
            return redirect(route('exams.create'))->withErrors($csvStructureValidator);
        }

        $examArray = $this->createArray(storage_path('app/' . $path));

        DB::beginTransaction();

        $exam = Exam::create([
            'type' => $examArray['type'],
            'source' => $examArray['source'],
            'description' => $examArray['description'],
        ]);

        $exam->test_id = $exam->create_test_id;
        $exam->save();

        foreach($examArray['answers'] as $question){
            try {
                ExamSection::create([
                    'exam_id' => $exam->id,
                    'section_number' => $question['Section #'] === "" ? NULL : $question['Section #'],
                    'question_number' => $question['Question #'] === "" ? NULL : $question['Question #'],
                    'correct_1' => $question['Correct Answer 1'] === "" ? NULL : $question['Correct Answer 1'],
                    'correct_2' => $question['Correct Answer 2'],
                    'correct_3' => $question['Correct Answer 3'],
                    'correct_4' => $question['Correct Answer 4'],
                    'correct_5' => $question['Correct Answer 5'],
                    'correct_6' => $question['Correct Answer 6'],
                    'correct_7' => $question['Correct Answer 7'],
                    'correct_8' => $question['Correct Answer 8'],
                    'correct_9' => $question['Correct Answer 9'],
                    'topic' => $question['Topic'],
                    'explanation' => $question['Answer Explanation'] === "" ? NULL : $question['Answer Explanation'],
                ]);
            } catch(\Exception $error) {
                DB::rollBack();
                $request->session()->flash('msg', ['type' => 'danger', 'text' => 'The Exam failed to upload: ' . $error->errorInfo[2]]);
                return redirect(route('exams.index'));
            }
        }

        try {
            ExamScoreTable::create([
                'exam_id' => $exam->id,
                'score_table' => json_encode($examArray['score'], JSON_FORCE_OBJECT),
            ]);
        } catch(\Exception $error) {
            DB::rollBack();
            $request->session()->flash('msg', ['type' => 'danger', 'text' => 'The Exam failed to upload: ' . $error->errorInfo[2]]);
            return redirect(route('exams.index'));
        }

        DB::commit();
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Exam was successfully created']);

        return redirect(route('exams.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        $item = $exam;
        return view('web.exam_prep.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exam_section_show(Exam $exam, $sectionId)
    {
        $examQuestions = $exam->sections->where('section_number', $sectionId);
        return view('web.exam_prep.show_exam_section', compact('examQuestions', 'exam'));
    }

    public function exam_section_edit(Exam $exam, $sectionId)
    {
        $examAnswers = $exam->sections->where('section_number', $sectionId)->values();
        return view('web.exam_prep.edit_exam_section', [
            'exam' => $exam,
            'examAnswers' => $examAnswers,
            'section' => $exam->sectionsMetadata->where('section_number', $sectionId)->first(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exam_section_update(Request $request, Exam $exam, $sectionId)
    {
        $examSections = $exam->sections->where('section_number', $sectionId)->values();
        $updatedAnswers = collect($request->all())->splice(1);

        $examSections->each(function($examSection, $key) use($updatedAnswers, $request) {
            $key = $key + 1;
            if (!is_array($updatedAnswers['question_' . $key])) {
                $examSection->correct_1 = $updatedAnswers['question_' . $key];
            } else {
                if ($request->has('question_' . $key . '.0')) {
                    $examSection->correct_1 = $request->input('question_' . $key . '.0');
                }
                $examSection->correct_2 = is_null($request->input('question_' . $key . '.1')) ? '' : $request->input('question_' . $key . '.1');
                $examSection->correct_3 = is_null($request->input('question_' . $key . '.2'))  ? '' : $request->input('question_' . $key . '.2');
                $examSection->correct_4 = is_null($request->input('question_' . $key . '.3'))  ? '' : $request->input('question_' . $key . '.3');
                $examSection->correct_5 = is_null($request->input('question_' . $key . '.4'))  ? '' : $request->input('question_' . $key . '.4');
                $examSection->correct_6 = is_null($request->input('question_' . $key . '.5'))  ? '' : $request->input('question_' . $key . '.5');
                $examSection->correct_7 = is_null($request->input('question_' . $key . '.6'))  ? '' : $request->input('question_' . $key . '.6');
                $examSection->correct_8 = is_null($request->input('question_' . $key . '.7'))  ? '' : $request->input('question_' . $key . '.7');
                $examSection->correct_9 = is_null($request->input('question_' . $key . '.8'))  ? '' : $request->input('question_' . $key . '.8');
            }
            $examSection->save();
        });

        return redirect(route('exams.section.show', [$exam->id, $sectionId]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if(StudentExam::where('exam_id', $id)->get()->isNotEmpty()) {
            $request->session()->flash('msg', ['type' => 'danger', 'text' => 'You are not allowed to delete this Exam because someone already answered it.']);
            return redirect(route('exams.index'));
        } else {
            $exam = Exam::find($id);
            $exam->delete();
            $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Exam was successfully deleted']);
            return redirect(route('exams.index'));
        }
    }

    public function logs()
    {
        $examSections = StudentExamSection::all()->sortByDesc('created_at');
        $exams = Exam::all();

        return view('web.exam_prep.logs', [
            'exams' => $exams,
            'examSections' => $examSections,
        ]);
    }

    public function generate_report(Request $request)
    {
        $requestCollection = collect($request->all())->splice(3)->all();
        $studentExamSections = StudentExamSection::all()->whereIn('id', $requestCollection);
        $answers = $studentExamSections->first()->studentExam->exam->sections->where('section_number', $request->input('section'));

        foreach($studentExamSections as $studentExamSection) {
            if(!$studentExamSection->time) {
                $studentExamSection->delete();
                return redirect()->back();
            } else {
                foreach ($studentExamSection->questions->unique('question_number') as $studentAnswer) {
                    $correct = $studentAnswer->correctAnswer;
                    $answersArray[] = [
                        'examSectionId' => $studentExamSection->id,
                        'questionNum' => $studentAnswer->question_number,
                        'studentAnswer' => $studentAnswer->answer,
                        'correctAnswer' => [$correct->correct_1, $correct->correct_2, $correct->correct_3, $correct->correct_4, $correct->correct_5],
                        'isCorrect' => $studentAnswer->AnswerResult,
                        'topic' => $correct->topic,
                    ];
                }
            }
        }

        $answersArrayByQuestion = collect($answersArray)->groupBy('questionNum');
        $answersArrayByTopic = collect($answersArray)->groupBy('topic');

        foreach($answersArrayByTopic as $topicName => $topicAnswers) {
            $scoreByTopic = 0;
            $numberOfQuestions = count($topicAnswers);
            foreach($topicAnswers as $topicAnswer) {
                if ($topicAnswer['isCorrect']) {
                    $scoreByTopic ++;
                };
            }
            $answersByTopic[$topicName] = [
                'topic' => $topicName,
                'score' => round(($scoreByTopic / $numberOfQuestions) * 100),
                'right' => $scoreByTopic,
                'wrong' => $numberOfQuestions - $scoreByTopic
            ];
        }

        foreach($answersArrayByQuestion as $questionAnswers) {
            $scoreByQuestion = 0;
            $numberOfStudents = count($questionAnswers);
            foreach($questionAnswers as $questionAnswer) {
                if($questionAnswer['isCorrect']) {
                    $scoreByQuestion++;
                }
            }
            $answersByQuestion[$questionAnswer['questionNum']] = [
                'score' => round(($scoreByQuestion/$numberOfStudents) *100),
            ];
        }

        return view('web.exam_prep.report', [
            'answers' => $answers,
            'answersByTopic' => $answersByTopic,
            'answersByQuestion' => $answersByQuestion,
            'studentExamSections' => $studentExamSections,
            'section' => $this->sections[$studentExamSection->section_number],
            'examId' => Exam::find($studentExamSection->studentExam->exam_id)->test_id,
        ]);
    }

    public function get_sections_for_results(Request $request)
    {
        $examId = $request->get('exam_id');
        $sectionId = $request->get('section_id');

        $studentExamIds = StudentExam::all()->where('exam_id', $examId)->pluck('id')->all();

        $studentExamSections = StudentExamSection::all()->whereIn('student_exam_id', $studentExamIds);
        $studentExamSections = $studentExamSections->where('section_number', $sectionId)->all();

        return response()->json($studentExamSections);
    }

    public function get_sections_for_exam(Request $request)
    {
        $examId = $request->get('exam_id');
        $exam = Exam::find($examId);

        return response()->json($exam->sectionsMetadata->toArray());
    }

    public function createArray($file)
    {
        $data = $this->csvToArray($file);

        $examType = $data[0][1];
        $examSource = $data[1][1];
        $examDescription = $data[2][1];

        if ($examType !== 'SAT' && $examType !== 'ACT') {
            $examFormat = $data[3][1];
            $examTime = $data[4][1];

            $answersTablePosition = collect($data)->collapse()->search('Section #')/13;
            $answersTableKeys = $data[$answersTablePosition];

            $data = array_slice($data, $answersTablePosition+1);
            $numberOfQuestions = count($data);
            $answersTableKeys[] = 'id';

            for ($i = 0; $i < $numberOfQuestions; $i++) {
                $data[$i][] = $i+1;
            }
            for ($j = 0; $j < $numberOfQuestions; $j++) {
                $d = array_combine($answersTableKeys, $data[$j]);
                $answersArray[$j] = $d;
            }

            $examArray = [
                'type' => $examType,
                'source' => $examSource,
                'description' => $examDescription,
                'answers' => $answersArray,
                'format' => $examFormat,
                'time' => $examTime,
            ];
        } else {
            $answersTablePosition = collect($data)->collapse()->search('Section #')/13;
            $answersTableKeys = $data[$answersTablePosition];

            $data = array_slice($data, $answersTablePosition+1);
            $scoreTablePosition = collect($data)->collapse()->search('Raw Score')/13;
            $scoreTableKeys = $data[$scoreTablePosition];

            $answersTableKeys[] = 'id';
            $scoreTableKeys[] = 'id';

            for ($i = 0; $i < $scoreTablePosition; $i++) {
                $data[$i][] = $i+1;
            }
            for ($j = 0; $j < $scoreTablePosition; $j++) {
                $d = array_combine($answersTableKeys, $data[$j]);
                $answersArray[$j] = $d;
            }

            $data = array_slice($data, $scoreTablePosition+1);

            for ($i = 0; $i < count($data); $i++) {
                $data[$i][] = $i+1;
            }
            for ($j = 0; $j < count($data); $j++) {
                $d = array_combine($scoreTableKeys, $data[$j]);
                $scoreArray[$j] = $d;
            }

            $examArray = [
                'type' => $examType,
                'source' => $examSource,
                'description' => $examDescription,
                'answers' => $answersArray,
                'score' => $scoreArray,
            ];
        }


        return $examArray;
    }

    public function csvToArray($file)
    {
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, ",", '"')) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        return $arr;
    }

    public function validateStructureCsv($csv_path)
    {
        ini_set('auto_detect_line_endings', true);
        $opened_file = fopen($csv_path, 'r');

        $type = fgetcsv($opened_file, 0, ',')[1];
        $source = fgetcsv($opened_file, 0, ',')[1];
        $description = fgetcsv($opened_file, 0, ',')[1];

        if ($type !== 'SAT' && $type !== 'ACT') {
            $format =  fgetcsv($opened_file, 0, ',')[1];
            $time = fgetcsv($opened_file, 0, ',')[1];
        }

        $header = fgetcsv($opened_file, 0, ',');

        fclose($opened_file);

        $validationRules = [
            'type' => 'required|string|alpha',
            'source' => 'required|string',
            'description' => 'required|string',
            'Section #' => 'required',
            'Question #' => 'required',
            'Correct Answer 1' => 'required',
            'Correct Answer 2' => 'required',
            'Correct Answer 3' => 'required',
            'Correct Answer 4' => 'required',
            'Correct Answer 5' => 'required',
            'Correct Answer 6' => 'required',
            'Correct Answer 7' => 'required',
            'Correct Answer 8' => 'required',
            'Correct Answer 9' => 'required',
            'topic' => 'required',
            'Answer Explanation' => 'required',
        ];

        $arrayToValidate = [
            'type' => $type,
            'source' => $source,
            'description' => $description,
            'Section #' => $this->getKeyByValue($header, 'Section #'),
            'Question #' => $this->getKeyByValue($header, 'Question #'),
            'Correct Answer 1' => $this->getKeyByValue($header, 'Correct Answer 1'),
            'Correct Answer 2' => $this->getKeyByValue($header, 'Correct Answer 2'),
            'Correct Answer 3' => $this->getKeyByValue($header, 'Correct Answer 3'),
            'Correct Answer 4' => $this->getKeyByValue($header, 'Correct Answer 4'),
            'Correct Answer 5' => $this->getKeyByValue($header, 'Correct Answer 5'),
            'Correct Answer 6' => $this->getKeyByValue($header, 'Correct Answer 6'),
            'Correct Answer 7' => $this->getKeyByValue($header, 'Correct Answer 7'),
            'Correct Answer 8' => $this->getKeyByValue($header, 'Correct Answer 8'),
            'Correct Answer 9' => $this->getKeyByValue($header, 'Correct Answer 9'),
            'topic' => $this->getKeyByValue($header, 'Topic'),
            'Answer Explanation' => $this->getKeyByValue($header, 'Answer Explanation'),
        ];

        if ($type !== 'SAT' && $type !== 'ACT') {
            $validationRules['type'] = 'required|string';
            $validationRules['format'] = 'required|string';
            $validationRules['time'] = 'required|integer';
            $arrayToValidate['format'] = $format;
            $arrayToValidate['time'] = $time;
        }

        $validator = Validator::make($arrayToValidate, $validationRules);

        return $validator;
    }

    private function getKeyByValue($array, $value)
    {
        return in_array($value, $array) ? $value : '';
    }

}
