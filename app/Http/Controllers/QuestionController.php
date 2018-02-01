<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Question;
use Brightfox\Models\Answer;
use Brightfox\Models\GradeLevel;
use Brightfox\Models\Tags;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use Brightfox\Traits\HasTags;
use File;
use Carbon\Carbon;

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
            'title' => 'required_without:photo_cropped',
            'answers' => 'required_unless:type,2,type,3,type,6|require_one_correct_for_multiple_choice:'.$request->input('type'), //Apple Pencil and Research and Report back don't need answers
            'answers.*.text' => 'required_without:answers.*.photo_cropped',
        ]);

        $question_type = $request->input('type');

        $question = Question::create([
            'type' => json_encode(['key' => $question_type, 'name' => $this->types[$question_type]], JSON_FORCE_OBJECT),
            'title' => $request->input('title'),
            'topic_id' => $request->input('topic'),
            'user_id' => auth()->user()->id
        ]);

        if ($request->has('photo_cropped') && $request->input('photo_cropped') != '') {
            $image_width = $this->question_type_resize($question_type);

            $question->photo = $this->createAndSavePhoto($request->input('photo_cropped'), Question::PHOTO_PATH, $image_width, null);
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
    
        if ($request->has('photo_cropped') && $request->input('photo_cropped') != '') {
            if (!is_null($question->getOriginal('photo')) || $question->getOriginal('photo') != '') {
                File::delete(public_path(Question::PHOTO_PATH . $question->getOriginal('photo')));
            }

            $image_width = $this->question_type_resize($question_type);

            $question->photo = $this->createAndSavePhoto($request->input('photo_cropped'), Question::PHOTO_PATH, $image_width, null);
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
        }

        $questions_query = Question::whereHas('topic', function ($query) use ($request) {
            $query->where('subject_id', $request->get('subject'));
        })->where('type', 'like', '%"key":"'.$type.'"%')->with('topic', 'tags');

        if ($request->get('created_at') != '') {
            $questions_query->where('created_at', 'like', Carbon::parse($request->input('created_at'))->format('Y-m-d').'%');
        }

        $questions = $questions_query->get();

        return response()->json($questions);
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
}
