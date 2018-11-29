<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Question;
use Brightfox\Models\Answer;
use Brightfox\Models\GradeLevel;
use Brightfox\Models\Tags;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use Brightfox\Traits\HasTags;
use File;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

;

class QuestionController extends Controller
{
    use CreatesAndSavesPhotos, HasTags;

    protected $types = Question::TYPES;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Question::with('topic.subject.grade_level');

        /* TABLE FILTERS */
        $filters = [
            'type' => $request->input('type'),
            'grade_level' => $request->input('grade_level'),
            'subject' =>  $request->input('subject'),
            'topic' => $request->input('topic'),
            'created_at' => $request->has('created_at')? Carbon::parse($request->input('created_at'))->format('Y-m-d') : null
        ];

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->search($search);
        }

        if (!is_null($filters['type'])) {
            $query->where('type', 'like', '%"key":"'.$filters['type'].'"%');
        }

        if (!is_null($filters['created_at'])) {
            $query->where('created_at', 'like', $filters['created_at'].'%');
            $filters['created_at'] = $request->has('created_at')? Carbon::parse($request->input('created_at'))->format('m/d/Y') : null;
        }

        if (!is_null($filters['topic'])) {
            $query->where('topic_id', $filters['topic']);
        } elseif (!is_null($filters['subject'])) {
            $query->whereHas('topic', function ($subquery) use ($filters) {
                $subquery->where('subject_id', $filters['subject']);
            });
        } elseif (!is_null($filters['grade_level'])) {
            $query->whereHas('topic', function ($subquery) use ($filters) {
                $subquery->whereHas('subject', function ($second_subquery) use ($filters) {
                    $second_subquery->where('grade_level_id', $filters['grade_level']);
                });
            });
        }

        /* TABLE SORTING */
        $sort_columns = [
            'title' => 'asc',
            'grade_level' => 'asc',
            'subject' => 'asc',
            'topic' => 'asc',
            'type' => 'asc',
            'user' => 'asc',
            'created_at' => 'asc',
        ];
        $sort = ['column' => 'created_at', 'value' => 'desc'];

        if ($request->has('sort_column')) {
            $sort = ['column' => $request->input('sort_column'), 'value' => $request->input('sort_value')];
            $sort_columns[$sort['column']] = ($sort['value'] == 'asc')? 'desc' : 'asc';
        }

        switch ($sort['column']) {
            case 'title':
                $query->orderBy($sort['column'], $sort['value']);
                break;

            case 'grade_level':
                $query->leftJoin('topics', 'topics.id', '=', 'questions.topic_id')
                    ->leftJoin('subjects', 'subjects.id', '=', 'topics.subject_id')
                    ->leftJoin('grade_levels', 'grade_levels.id', '=', 'subjects.grade_level_id')
                    ->orderBy('grade_levels.name', $sort['value']);
                break;

            case 'subject':
                $query->leftJoin('topics', 'topics.id', '=', 'questions.topic_id')
                    ->leftJoin('subjects', 'subjects.id', '=', 'topics.subject_id')
                    ->orderBy('subjects.name', $sort['value']);
                break;

            case 'topic':
                $query->leftJoin('topics', 'topics.id', '=', 'questions.topic_id')
                    ->orderBy('topics.name', $sort['value']);
                break;

            case 'type':
                $query->orderBy('type', $sort['value']);
                break;
    
            case 'user':
                $query->leftJoin('users', 'users.id', '=', 'questions.user_id')
                    ->orderBy('users.name', $sort['value']);
                break;

            case 'created_at':
                $query->orderBy('created_at', $sort['value']);
                break;
        }

        $list = $query->paginate(50);
        $grade_levels = GradeLevel::all();
        $types = $this->types;
        $filter_string = http_build_query($filters);

        return view('web.questions.index', compact('list', 'search', 'grade_levels', 'types', 'filters', 'sort_columns', 'filter_string'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.questions.create', [
            'grade_levels' => GradeLevel::all(),
            'types' => $this->types,
            'prefilled_fields' => [
                'type' => null,
                'grade_level' => null,
                'subject' => null,
                'topic' => null,
                'title'=>null,
                'tags'=>null
            ]
        ]);
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
            'type' => 'required',
            'topic' => 'required',
            'title' => 'required_without_all:photo_cropped,other_photo_cropped|required_if:type,7',
            'answers' => 'required_unless:type,2,type,3,type,6|require_one_correct_for_multiple_choice:'.$request->input('type'), //Apple Pencil and Research and Report back don't need answers
            'answers.*.text' => 'required_without:answers.*.photo_cropped',
            'other_photo_cropped' => 'required_if:type,7',
        ]);

        $question_type = $request->input('type');

        $question = Question::create([
            'type' => json_encode(['key' => $question_type, 'name' => $this->types[$question_type]], JSON_FORCE_OBJECT),
            'title' => $request->input('title'),
            'topic_id' => $request->input('topic'),
            'user_id' => auth()->user()->id
        ]);

        if ($request->has('answer_explanation') && $request->input('answer_explanation') != '') {
            $question->answer_explanation = $request->input('answer_explanation');
            $question->save();
        }

        if ($request->has('photo_cropped') && $request->input('photo_cropped') != '') {
            $image_width = $this->question_type_resize($question_type);

            $question->photo = $this->createAndSavePhoto($request->input('photo_cropped'), Question::PHOTO_PATH, $image_width, null);
            $question->save();
        }

        if ($request->has('other_photo_cropped') && $request->input('other_photo_cropped') != '') {
            $image_size = getimagesize($request->input('other_photo_cropped'));
            $image_width = $image_size[0];
            $image_height = $image_size[1];

            $question->other_photo = $this->createAndSavePhoto($request->input('other_photo_cropped'), Question::PHOTO_PATH, $image_width, $image_height);

            $question->save();
        }

        if ($request->has('answer_explanation_photo_cropped') && $request->input('answer_explanation_photo_cropped') != '') {
            $image_size = getimagesize($request->input('answer_explanation_photo_cropped'));
            $image_width = $image_size[0];
            $image_height = $image_size[1];

            $question->answer_explanation_photo = $this->createAndSavePhoto($request->input('answer_explanation_photo_cropped'), Question::PHOTO_PATH, $image_width, $image_height);
            $question->save();
        }

        if ($request->has('answers')) {
            foreach ($request->input('answers') as $key => $request_answer) {
                $answer = Answer::create([
                    'text' => $request_answer['text'],
                    'is_correct' => (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0,
                    'group' => (array_key_exists('group', $request_answer))? $request_answer['group'] : '',
                    'question_id' => $question->id
                ]);

                if ($request->has('answers.'.$key.'.photo_cropped') && $request->input('answers.'.$key.'.photo_cropped')) {
                    $answer->photo = $this->createAndSavePhoto($request->input('answers.'.$key.'.photo_cropped'), Answer::PHOTO_PATH, null, null);
                    $answer->save();
                }

                if ($request->has('answers.'.$key.'.obj_data')) {
                    $answer->object_data = $request->input('answers.'.$key.'.obj_data');
                    $answer->save();
                }
            }
        }

        if ($request->has('tags')) {
            $question->tags()->sync($this->getTagsToSync($request->input('tags')));
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully created']);

        if ($request->has('add_more')) {
            return view('web.questions.create', [
                'grade_levels' => GradeLevel::all(),
                'types' => $this->types,
                'prefilled_fields' => [
                    'type' => $request->input('type'),
                    'grade_level' => $request->input('grade_level'),
                    'subject' => $request->input('subject'),
                    'topic' => $request->input('topic'),
                    'tags' =>  $request->input('tags'),
                    'title' =>$request->input('title')
                ]
            ]);
        } else {
            return redirect(route('questions.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        return view('web.questions.show', ['item' => $question]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        return view('web.questions.edit', [
            'item' => $question,
            'grade_levels' => GradeLevel::all(),
            'types' => $this->types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        $this->validate($request, [
            'type' => 'required',
            'topic' => 'required',
            'answers' => 'required_unless:type,3|require_one_correct_for_multiple_choice:'.$request->input('type'),
            'answers.*.text' => 'required_without_all:answers.*.id,answers.*.photo_cropped|required_with:answers.*.remove_photo',
        ]);

        $question_type = $request->input('type');

        $question->type = json_encode(['key' => $question_type, 'name' => $this->types[$question_type]], JSON_FORCE_OBJECT);
        $question->title = $request->input('title');
        $question->topic_id = $request->input('topic');
        $question->save();

        if ($request->has('answer_explanation') && $request->input('answer_explanation') != '') {
            $question->answer_explanation = $request->input('answer_explanation');
            $question->save();
        }
    
        if ($request->has('photo_cropped') && $request->input('photo_cropped') != '') {
            if (!is_null($question->getOriginal('photo')) || $question->getOriginal('photo') != '') {
                File::delete(public_path(Question::PHOTO_PATH . $question->getOriginal('photo')));
            }

            $image_width = $this->question_type_resize($question_type);

            $question->photo = $this->createAndSavePhoto($request->input('photo_cropped'), Question::PHOTO_PATH, $image_width, null);
            $question->save();
        }

        if ($request->has('other_photo_cropped') && $request->input('other_photo_cropped') != '') {
            if (!is_null($question->getOriginal('other_photo')) || $question->getOriginal('other_photo') != '') {
                File::delete(public_path(Question::PHOTO_PATH . $question->getOriginal('other_photo')));
            }
            $image_size = getimagesize($request->input('other_photo_cropped'));
            $image_width = $image_size[0];
            $image_height = $image_size[1];

            $question->other_photo = $this->createAndSavePhoto($request->input('other_photo_cropped'), Question::PHOTO_PATH, $image_width, $image_height);
            $question->save();
        }

        if ($request->has('answer_explanation_photo_cropped') && $request->input('answer_explanation_photo_cropped') != '') {
            $image_size = getimagesize($request->input('answer_explanation_photo_cropped'));
            $image_width = $image_size[0];
            $image_height = $image_size[1];

            $question->answer_explanation_photo = $this->createAndSavePhoto($request->input('answer_explanation_photo_cropped'), Question::PHOTO_PATH, $image_width, $image_height);
            $question->save();
        }

        $answers_ids = collect($request->get('answers'))->map(function ($answer) {
            return $answer['id'];
        })->toArray();

        $answers_to_delete = $question->answers()->whereNotIn('id', $answers_ids)->get()->each(function ($answer, $key) {
            $answer->delete();
        });

        if ($request->has('answers')) {
            
            foreach ($request->input('answers') as $key => $request_answer) {
                if (!is_null($request_answer['id'])) {
                    $answer = Answer::find($request_answer['id']);
                    $answer->text = $request_answer['text'];
                    $answer->is_correct = (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0;

                    if (array_key_exists('remove_photo', $request_answer) && !is_null($request_answer['remove_photo'])) {
                        if (!is_null($answer->getOriginal('photo')) || $answer->getOriginal('photo') != '') {
                            
                            File::delete(public_path(Answer::PHOTO_PATH . $answer->getOriginal('photo')));
                        }
                        $answer->photo = null;
                    }

                    $answer->save();
                } else {
                    $answer = Answer::create([
                        'text' => $request_answer['text'],
                        'is_correct' => (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0,
                        'question_id' => $question->id
                    ]);
                }
    
                if ($request->has('answers.'.$key.'.photo_cropped') && $request->input('answers.'.$key.'.photo_cropped')) {
                    if (!is_null($answer->getOriginal('photo')) || $answer->getOriginal('photo') != '') {
                        File::delete(public_path(Answer::PHOTO_PATH . $answer->getOriginal('photo')));
                    }

                    $answer->photo = $this->createAndSavePhoto($request->input('answers.'.$key.'.photo_cropped'), Answer::PHOTO_PATH, null, null);
                    $answer->save();
                }

                if ($request->has('answers.'.$key.'.obj_data')) {
                    $answer->object_data = $request->input('answers.'.$key.'.obj_data');
                    $answer->save();
                }
            }
        }

        if ($request->has('tags')) {
            $question->tags()->sync($this->getTagsToSync($request->input('tags')));
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully edited']);

        return redirect(route('questions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Question $question)
    {
        foreach ($question->answers as $answer) {
            $answer->delete();
        }

        $question->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully deleted']);

        return redirect(route('questions.index'));
    }

    public function get_questions_for_quiz(Request $request)
    {
        //Match quiz types keys with question types keys
        switch ($request->get('type')) {
            case '0':
            case '1':
            case '2':
            case '3':
                $type = '0'; //Multiple Choice
                break;
            case '4':
                $type = '1'; //Fill in the blank
                break;
            case '5':
            case '6':
            case '7':
                $type = '2'; //Trivia
                break;
            case '8':
                $type = '3'; //Apple Pencil
                break;
            case '9':
                $type = '4'; //Drag and Drop
                break;
            case '10':
                $type = '5'; //Touch Select
                break;
            case '11':
                $type = '6'; //Research and Report back
                break;
            case '12': //Long Passage
                $type = '7';
                break;
        }

        $questions_query = Question::whereHas('topic', function ($query) use ($request) {
            $query->where('subject_id', $request->get('subject'));
        })->where('type', 'like', '%"key":"'.$type.'"%')->with('topic', 'tags');

        if ($request->get('created_at') != '') {
            $questions_query->where('created_at', 'like', Carbon::parse($request->input('created_at'))->format('Y-m-d').'%');
        }
        if ($request->get('tags') != []) {
            $questionsWithTags = [];

            foreach($questions_query->get() as $question) {
                $tags = $question->tags->pluck('id')->toArray();
                foreach($tags as $tag) {
                    if(in_array($tag-1, $request->get('tags'))) {
                        $questionsWithTags[] = $question;
                    }
                }
            }
            return response()->json($questionsWithTags);
        } else {
            $questions = $questions_query->get();
            return response()->json($questions);
        }
    }

    public function question_type_resize($type)
    {
        $width = 580;

        switch ($type) {
            case 0:
            case 1:
            case 3:
            case 4:
            case 5:
            case 6:
                $width = null; // 10/2017 removed resizing of all question types because of problems with Unity
                break;
        }

        return $width;
    }

    public function csv_question_importer()
    {
        return view('web.questions.csv_importer');
    }

    public function store_csv(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('csv')->store('/data');
        $csvStructureValidator = $this->validateStructureCsv(storage_path('app/' . $path));

        if ($csvStructureValidator->fails()) {
            return redirect(route('questions.csv_importer'))->withErrors($csvStructureValidator);
        }

        $questionsArray = $this->createArray(storage_path('app/' . $path));
        DB::beginTransaction();

        foreach ($questionsArray as $csvQuestion) {
            $type_key = $this->getQuestionTypeKey($csvQuestion['type']);

            try {
                $question = Question::create([
                    'type' => json_encode(['key' => strval($type_key), 'name' => $this->types[$type_key]], JSON_FORCE_OBJECT),
                    'title' => $csvQuestion['title'],
                    'topic_id' => $csvQuestion['topic_id'],
                    'user_id' => auth()->user()->id,
                    'answer_explanation' => $csvQuestion['explanation'] ? $csvQuestion['explanation'] : NULL,
                ]);

            } catch (\Exception $error) {
                DB::rollBack();
                $request->session()->flash('msg', ['type' => 'danger', 'text' => 'Something went wrong, please check your CSV File']);
                return redirect(route('questions.csv_importer'));
            }

            for ($i = 1; $i < 6; $i++){
                if($csvQuestion['answer_' . $i]){
                    try {
                        Answer::create([
                            'text' => $csvQuestion['answer_' .$i],
                            'is_correct' => ($csvQuestion['correct'] == $i) ? 1 : 0,
                            'question_id' => $question->id
                        ]);
                    } catch (\Exception $error) {
                        DB::rollBack();
                        $request->session()->flash('msg', ['type' => 'danger', 'text' => 'Something went wrong, please check your CSV File']);
                        return redirect(route('questions.csv_importer'))->back();
                    }
                }
            }

            if ($csvQuestion['tags']) {
                try {
                    $question->tags()->sync($this->getTagsToSync(explode(',', $csvQuestion['tags'])));
                } catch (\Exception $error) {
                    DB::rollBack();
                    $request->session()->flash('msg', ['type' => 'danger', 'text' => 'Something went wrong, please check your CSV File']);
                    return redirect(route('questions.csv_importer'))->back();
                }
            }
        }

        DB::commit();
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'Questions were successfully created']);
        return redirect(route('questions.index'));
    }

    public function createArray($file)
    {
        $csvData = $this->csvToArray($file);
        $keys = $csvData[0];

        $csvData = array_slice($csvData, '1');
        $keys[] = 'id';

        for ($i = 0; $i < count($csvData); $i++) {
            $csvData[$i][] = $i+1;
        }
        for ($j = 0; $j < count($csvData); $j++) {
            $d = array_combine($keys, $csvData[$j]);
            $questionsArray[$j] = $d;
        }

        return $questionsArray;
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
        $header = fgetcsv($opened_file, 0, ',');
        fclose($opened_file);

        $validationRules = [
            'type' => 'required',
            'topic_id' => 'required',
            'tags' => 'required',
            'title' => 'required',
            'answer_1' => 'required',
            'answer_2' => 'required',
            'answer_3' => 'required',
            'answer_4' => 'required',
            'answer_5' => 'required',
            'correct' => 'required',
            'explanation' => 'required'
        ];

        $arrayToValidate = [
            'type' => $this->getKeyByValue($header, 'type'),
            'topic_id' => $this->getKeyByValue($header, 'topic_id'),
            'tags' => $this->getKeyByValue($header, 'tags'),
            'title' => $this->getKeyByValue($header, 'title'),
            'answer_1' => $this->getKeyByValue($header, 'answer_1'),
            'answer_2' => $this->getKeyByValue($header, 'answer_2'),
            'answer_3' => $this->getKeyByValue($header, 'answer_3'),
            'answer_4' => $this->getKeyByValue($header, 'answer_4'),
            'answer_5' => $this->getKeyByValue($header, 'answer_5'),
            'correct' => $this->getKeyByValue($header, 'correct'),
            'explanation' => $this->getKeyByValue($header, 'explanation'),
        ];

        $validator = Validator::make($arrayToValidate, $validationRules);

        return $validator;
    }

    private function getKeyByValue($array, $value)
    {
        return in_array($value, $array) ? $value : '';
    }

    private function getQuestionTypeKey ($type)
    {
        switch ($type) {
            case 'multiplechoice':
                $type = 'Multiple choice';
                break;
            case 'filltheblank':
                $type = 'Fill the blank';
                break;
            case 'trivia':
                $type = 'Trivia';
                break;
            case 'penpal':
                $type = 'PenPal';
                break;
            case 'draganddrop':
                $type = 'Drag and drop';
                break;
            case 'taptime':
                $type = 'Tap Time';
                break;
            case 'researchandreportback':
                $type = 'Research and report back';
                break;
            case 'longpassage':
                $type = 'Long Passage';
                break;
            default:
                $type = 0;
        }

        return $type ? array_keys($this->types, $type)[0] : 10;
    }
}
