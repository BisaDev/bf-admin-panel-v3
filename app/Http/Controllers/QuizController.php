<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Quiz, Brightfox\Question, Brightfox\GradeLevel, Brightfox\Tag;
use Illuminate\Http\Request;
use Brightfox\Traits\HasTags;

class QuizController extends Controller
{
    use HasTags;

    protected $types = Quiz::TYPES;

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
            $list = Quiz::search($search)->with('subject.grade_level', 'questions')->paginate(10);
        }else{
            $list = Quiz::with('subject.grade_level')->paginate(10);
        }

        return view('web.quizzes.index', compact('list', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.quizzes.create', [
            'grade_levels' => GradeLevel::all(),
            'types' => $this->types,
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
            'title' => 'required|string|max:191',
            'subject' => 'required',
            'questions' => 'required',
        ]);

        $quiz = Quiz::create([
            'type' => json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'subject_id' => $request->input('subject')
        ]);

        if($request->has('questions')){
            $quiz->questions()->sync($request->input('questions'));
        }

        if($request->has('tags')){
            $quiz->tags()->sync($this->getTagsToSync($request->input('tags')));
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Quiz was successfully created']);

        return redirect(route('quizzes.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        return view('web.quizzes.show', ['item' => $quiz]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function reorder_questions(Quiz $quiz)
    {
        return view('web.quizzes.reorder', ['item' => $quiz]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        return view('web.quizzes.edit', [
            'item' => $quiz,
            'grade_levels' => GradeLevel::all(),
            'types' => $this->types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->validate($request, [
            'type' => 'required',
            'title' => 'required|string|max:191',
            'subject' => 'required',
            'questions' => 'required',
        ]);

        
        $quiz->type = json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT);
        $quiz->title = $request->input('title');
        $quiz->description = $request->input('description');
        $quiz->subject_id = $request->input('subject');
        $quiz->save();

        if($request->has('questions')){
            $quiz->questions()->sync($request->input('questions'));
        }

        if($request->has('tags')){
            $quiz->tags()->sync($this->getTagsToSync($request->input('tags')));
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Quiz was successfully edited']);

        return redirect(route('quizzes.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Quiz $quiz)
    {
        $quiz->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Quiz was successfully deleted']);
        
        return redirect(route('quizzes.index'));
    }

    public function get_quizzes_for_activity_bucket(Request $request)
    {
        $quizzes = Quiz::where('subject_id', $request->get('subject'))->with('subject', 'tags')->get();

        return response()->json($quizzes);
    }

    public function save_question_order(Request $request)
    {
        $quiz = Quiz::find($request->input('quiz_id'));
        
        foreach ($request->input('questions') as $key => $question) {
            $quiz->questions()->updateExistingPivot($question['id'], ['order' => $key+1]);
        }
    }
}
