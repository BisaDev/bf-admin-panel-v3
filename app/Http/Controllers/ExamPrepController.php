<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Brightfox\Models\Exam, Brightfox\Models\ExamSection;

class ExamPrepController extends Controller
{
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
            return redirect(route('exams.create'))
                ->withErrors($csvStructureValidator);
        }

        $examArray = $this->createArray(storage_path('app/' . $path));

        DB::beginTransaction();

        $exam = Exam::create([
            'type' => $examArray[0]
        ]);
        $exam->test_id = $exam->create_test_id;
        $exam->save();

        array_shift($examArray);

        foreach($examArray as $question){
            try {
                ExamSection::create([
                    'exam_id' => $exam->id,
                    'section_number' => $question['section_number'] === "" ? NULL : $question['section_number'],
                    'question_number' => $question['question_number'] === "" ? NULL : $question['question_number'],
                    'correct_1' => $question['correct_1'] === "" ? NULL : $question['correct_1'],
                    'correct_2' => $question['correct_2'],
                    'correct_3' => $question['correct_3'],
                    'correct_4' => $question['correct_4'],
                    'correct_5' => $question['correct_5'],
                    'topic' => $question['topic'],
                ]);
            } catch(\Exception $error) {
                DB::rollBack();
                $request->session()->flash('msg', ['type' => 'danger', 'text' => 'The Exam failed to upload: ' . $error->errorInfo[2]]);
                return redirect(route('exams.index'));
            }
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function createArray($file)
    {
        $feed = $file;
        $keys[] = [];
        $data = $this->csvToArray($feed);
        $count = count($data) - 2;
        $examType = $data[0][0];
        $keys = $data[1];
        $data = array_slice($data, 2);
        $tmpData = [];

        $keys[] = 'id';
        for ($i = 0; $i < $count; $i++) {
            $data[$i][] = $i+1;
        }

        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $data[$j]);
            $tmpData[$j] = $d;
        }

        array_unshift($tmpData, $examType);

        return $tmpData;
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

        $type = fgetcsv($opened_file, 0, ',')[0];
        $header = fgetcsv($opened_file, 0, ',');

        fclose($opened_file);

        $validationRules = [
            'type' => 'required|string|alpha',
            'section_number' => 'required',
            'question_number' => 'required',
            'correct_1' => 'required',
            'correct_2' => 'required',
            'correct_3' => 'required',
            'correct_4' => 'required',
            'correct_5' => 'required',
            'topic' => 'required',
        ];

        $arrayToValidate = [
            'type' => $type,
            'section_number' => $this->getKeyByValue($header, 'section_number'),
            'question_number' => $this->getKeyByValue($header, 'question_number'),
            'correct_1' => $this->getKeyByValue($header, 'correct_1'),
            'correct_2' => $this->getKeyByValue($header, 'correct_2'),
            'correct_3' => $this->getKeyByValue($header, 'correct_3'),
            'correct_4' => $this->getKeyByValue($header, 'correct_4'),
            'correct_5' => $this->getKeyByValue($header, 'correct_5'),
            'topic' => $this->getKeyByValue($header, 'topic'),
        ];

        $validator = Validator::make($arrayToValidate, $validationRules);

        return $validator;
    }

    private function getKeyByValue($array, $value)
    {
        return in_array($value, $array) ? $value : '';
    }
}
