<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Question, Brightfox\Answer, Brightfox\GradeLevel;
use Illuminate\Http\Request;
use File;

class QuestionController extends Controller
{
    protected $types = Question::TYPES;

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $search = $request->input('search');
            $list = Question::search($search)->with('topic.subject.grade_level')->paginate(10);
        }else{
            $list = Question::with('topic.subject.grade_level')->paginate(10);
        }

        return view('web.questions.index', compact('list', 'search'));
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
                'topic' => null
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
            'title' => 'required|string',
            'topic' => 'required',
            'answers.*.text' => 'required_without:answers.*.photo',
        ]);

        $question = Question::create([
            'type' => json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT),
            'title' => $request->input('title'),
            'topic_id' => $request->input('topic')
        ]);

        if ($request->hasFile('photo')) {
            $question->photo = $this->createAndSavePhoto($request->file('photo'), Question::PHOTO_PATH, 400, null);
            $question->save();
        }

        if($request->has('answers')){
            foreach ($request->input('answers') as $key => $request_answer) {
                
                $answer = Answer::create([
                    'text' => $request_answer['text'], 
                    'is_correct' => (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0,
                    'question_id' => $question->id
                ]);

                if ($request->hasFile('answers.'.$key.'.photo')) {
                    $answer->photo = $this->createAndSavePhoto($request->file('answers.'.$key.'.photo'), Answer::PHOTO_PATH, 400, null);
                    $answer->save();
                }
            }
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully created']);

        if($request->has('add_more')){
            return view('web.questions.create', [
                'grade_levels' => GradeLevel::all(),
                'types' => $this->types,
                'prefilled_fields' => [
                    'type' => $request->input('type'), 
                    'grade_level' => $request->input('grade_level'), 
                    'subject' => $request->input('subject'),
                    'topic' => $request->input('topic')
                ]
            ]);
        }else{
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
            'title' => 'required|string',
            'topic' => 'required',
            'answers.*.text' => 'required_without_all:answers.*.id,answers.*.photo',
        ]);
        
        $question->type = json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT);
        $question->title = $request->input('title');
        $question->topic_id = $request->input('topic');
        $question->save();

        if ($request->hasFile('photo')) {
            if(!is_null($question->getOriginal('photo')) || $question->getOriginal('photo') != ''){
                File::delete(public_path(Question::PHOTO_PATH . $question->getOriginal('photo')));
            }

            $question->photo = $this->createAndSavePhoto($request->file('photo'), Question::PHOTO_PATH, 400, null);
            $question->save();
        }

        $answers_ids = collect($request->get('answers'))->map(function($answer){
            return $answer['id'];
        })->toArray();

        $answers_to_delete = $question->answers()->whereNotIn('id', $answers_ids)->get();
        foreach ($answers_to_delete as $answer) {
            $answer->delete();
        }

        if($request->has('answers')){
            foreach ($request->input('answers') as $key => $request_answer) {
                if(!is_null($request_answer['id'])){

                    $answer = Answer::find($request_answer['id']);
                    $answer->text = $request_answer['text'];
                    $answer->is_correct = (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0;
                    $answer->save();

                }else{
                    $answer = Answer::create([
                        'text' => $request_answer['text'], 
                        'is_correct' => (array_key_exists('is_correct', $request_answer) && !is_null($request_answer['is_correct']))? 1 : 0,
                        'question_id' => $question->id
                    ]);
                }

                if ($request->hasFile('answers.'.$key.'.photo')) {
                    if(!is_null($answer->getOriginal('photo')) || $answer->getOriginal('photo') != ''){
                        File::delete(public_path(Answer::PHOTO_PATH . $answer->getOriginal('photo')));
                    }

                    $answer->photo = $this->createAndSavePhoto($request->file('answers.'.$key.'.photo'), Answer::PHOTO_PATH, 400, null);
                    $answer->save();
                }
            }
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully edited']);

        return redirect(route('questions.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        foreach ($question->answers as $answer) {
            $answers->delete();
        }

        $question->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Question was successfully deleted']);
        
        return redirect(route('questions.index'));
    }
}
