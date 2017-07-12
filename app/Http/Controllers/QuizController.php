<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Quiz, Brightfox\Models\Question, Brightfox\Models\GradeLevel, Brightfox\Models\Tag;
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
        $query = Quiz::with('subject.grade_level');
    
        /* TABLE FILTERS */
        $filters = [
            'type' => $request->input('type'), 
            'grade_level' => $request->input('grade_level'), 
            'subject' =>  $request->input('subject')
        ];

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }

        if($request->has('type')){
            $filters['type'] = $request->input('type');
            $query->where('type', 'like', '%"key":"'.$filters['type'].'"%');
        }

        if(!is_null($filters['subject'])){
            $query->where('subject_id', $filters['subject']);
        }elseif(!is_null($filters['grade_level'])){
            $query->whereHas('subject', function ($subquery)use($filters) {
                $subquery->where('grade_level_id', $filters['grade_level']);
            });
        }
    
        /* TABLE SORTING */
        $sort_columns = [
            'title' => 'asc',
            'grade_level' => 'asc',
            'subject' => 'asc',
            'type' => 'asc',
        ];
        $sort = ['column' => 'id', 'value' => 'desc'];
    
        if($request->has('sort_column')){
            $sort = ['column' => $request->input('sort_column'), 'value' => $request->input('sort_value')];
            $sort_columns[$sort['column']] = ($sort['value'] == 'asc')? 'desc' : 'asc';
        }
    
        switch ($sort['column']) {
            case 'title':
                $query->orderBy($sort['column'], $sort['value']);
                break;
        
            case 'grade_level':
                $query->leftJoin('subjects','subjects.id','=','quizzes.subject_id')
                    ->leftJoin('grade_levels','grade_levels.id','=','subjects.grade_level_id')
                    ->orderBy('grade_levels.name', $sort['value']);
                break;
        
            case 'subject':
                $query->leftJoin('subjects','subjects.id','=','quizzes.subject_id')
                    ->orderBy('subjects.name', $sort['value']);
                break;
        
            case 'type':
                $query->orderBy('type', $sort['value']);
                break;
        }

        $list = $query->paginate(50);
        $grade_levels = GradeLevel::all();
        $types = $this->types;
        $filter_string = http_build_query($filters);

        return view('web.quizzes.index', compact('list', 'search', 'grade_levels', 'types', 'filters', 'sort_columns', 'filter_string'));
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
    
    public function reorder_questions(Quiz $quiz)
    {
        return view('web.quizzes.reorder', ['item' => $quiz]);
    }
    
    public function show_print(Quiz $quiz)
    {
        return view('web.quizzes.show_print', ['item' => $quiz]);
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
