<?php

namespace Brightfox\Http\Controllers;

use Brightfox\ActivityBucket, Brightfox\GradeLevel, Brightfox\Meetup, Brightfox\Minigame;
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
        if($request->has('search')){
            $search = $request->input('search');
            $list = ActivityBucket::search($search)->with('subject.grade_level', 'questions')->paginate(10);
        }else{
            $list = ActivityBucket::with('subject.grade_level')->paginate(10);
        }

        return view('web.activity_buckets.index', compact('list', 'search'));
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
            'title' => 'required|string|size:191',
            'subject' => 'required',
            'quizzes' => 'required',
        ]);

        $activity_bucket = ActivityBucket::create([
            'title' => $request->input('title'),
            'subject_id' => $request->input('subject')
        ]);

        if($request->has('quizzes')){
            $activity_bucket->quizzes()->sync($request->input('quizzes'));
        }

        if($request->has('meetup_id')){
            $meetup = Meetup::find($request->input('meetup_id'));
            $meetup->activity_bucket()->associate($activity_bucket);
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
            'title' => 'required|string|size:191',
            'subject' => 'required',
            'quizzes' => 'required',
        ]);

        
        $activity_bucket->title = $request->input('title');
        $activity_bucket->subject_id = $request->input('subject');
        $activity_bucket->save();

        if($request->has('quizzes')){
            $activity_bucket->quizzes()->sync($request->input('quizzes'));
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
        
        return redirect(route('activity_bucket.index'));
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
