<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\ActivityBucket, Brightfox\Models\GradeLevel, Brightfox\Models\Meetup, Brightfox\Models\Minigame;
use Illuminate\Http\Request;

class ActivityBucketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ActivityBucket::with('subject.grade_level');
    
        /* TABLE FILTERS */
        $filters = [
            'grade_level' => $request->input('grade_level'), 
            'subject' =>  $request->input('subject')
        ];

        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        } else {
            $search = '';
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
                $query->leftJoin('subjects','subjects.id','=','activity_buckets.subject_id')
                    ->leftJoin('grade_levels','grade_levels.id','=','subjects.grade_level_id')
                    ->orderBy('grade_levels.name', $sort['value']);
                break;
        
            case 'subject':
                $query->leftJoin('subjects','subjects.id','=','activity_buckets.subject_id')
                    ->orderBy('subjects.name', $sort['value']);
                break;
        }

        $list = $query->paginate(50);
        $grade_levels = GradeLevel::all();
        $filter_string = http_build_query($filters);

        return view('web.activity_buckets.index', compact('list', 'search', 'grade_levels', 'filters', 'sort_columns', 'filter_string'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($meetup_id = null)
    {
        $meetup = Meetup::find($meetup_id);

        return view('web.activity_buckets.create', [
            'grade_levels' => GradeLevel::all(),
            'minigames' => Minigame::all(),
            'meetup' => $meetup
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
            'title' => 'required|string|max:191',
            'presentation_file' => 'nullable|url',
            'subject' => 'required',
            'quizzes' => 'required',
        ]);

        $activity_bucket = ActivityBucket::create([
            'title' => $request->input('title'),
            'presentation_file' => $request->input('presentation_file'),
            'subject_id' => $request->input('subject')
        ]);

        if($request->has('quizzes')){
            $quizzes_to_sync = array_intersect_key($request->input('quizzes_minigames'), $request->input('quizzes'));
            $activity_bucket->quizzes()->sync($quizzes_to_sync);
        }

        if($request->has('meetup_id')){
            $meetup = Meetup::find($request->input('meetup_id'));
            $meetup->activity_bucket()->associate($activity_bucket);
            
            if($meetup->students->count() > 0){
                $meetup->status = json_encode(['key' => '1', 'name' => 'Ready'], JSON_FORCE_OBJECT);
            }

            $meetup->save();

            $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Activity Bucket was created and associated with the Meetup']);
            $redirect = route('meetups.index');
        }else{
            $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Activity Bucket was successfully created']);
            $redirect = route('activity_buckets.index');
        }

        return redirect($redirect);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\ActivityBucket  $activity_bucket
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityBucket $activity_bucket)
    {
        return view('web.activity_buckets.show', ['item' => $activity_bucket]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\ActivityBucket  $activity_bucket
     * @return \Illuminate\Http\Response
     */
    public function reorder_quizzes(ActivityBucket $activity_bucket)
    {
        return view('web.activity_buckets.reorder', ['item' => $activity_bucket]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\ActivityBucket  $activity_bucket
     * @return \Illuminate\Http\Response
     */
    public function edit(ActivityBucket $activity_bucket)
    {
        return view('web.activity_buckets.edit', [
            'item' => $activity_bucket,
            'grade_levels' => GradeLevel::all(),
            'minigames' => Minigame::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\ActivityBucket  $activity_bucket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ActivityBucket $activity_bucket)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'presentation_file' => 'nullable|url',
            'subject' => 'required',
            'quizzes' => 'required',
        ]);

        
        $activity_bucket->title = $request->input('title');
        $activity_bucket->subject_id = $request->input('subject');
        $activity_bucket->presentation_file = $request->input('presentation_file');
        $activity_bucket->save();

        if($request->has('quizzes')){
            $quizzes_to_sync = array_intersect_key($request->input('quizzes_minigames'), $request->input('quizzes'));
            $activity_bucket->quizzes()->sync($quizzes_to_sync);
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Activity Bucket was successfully edited']);

        return redirect(route('activity_buckets.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\ActivityBucket  $activity_bucket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ActivityBucket $activity_bucket)
    {
        $activity_bucket->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Activity Bucket was successfully deleted']);
        
        return redirect(route('activity_buckets.index'));
    }

    public function get_activity_buckets_for_meetup(Request $request)
    {
        $activity_buckets = ActivityBucket::where('subject_id', $request->get('subject'))->get();

        return response()->json($activity_buckets);
    }

    public function save_quiz_order(Request $request)
    {
        $activity_bucket = ActivityBucket::find($request->input('activity_bucket_id'));
        
        foreach ($request->input('quizzes') as $key => $quiz) {
            $activity_bucket->quizzes()->updateExistingPivot($quiz['id'], ['order' => $key+1]);
        }
    }
}
