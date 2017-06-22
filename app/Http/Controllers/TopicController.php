<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\Subject;
use Brightfox\Topic;

class TopicController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $subject_id
     * @return \Illuminate\Http\Response
     */
    public function create($subject_id)
    {
        $subject = Subject::find($subject_id);

        return view('web.topics.create', compact('subject'));
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
            'name' => 'required|string|max:191',
            'subject_id' => 'required|numeric',
        ]);
        
        Topic::create($request->only(['name', 'subject_id']));

        if($request->has('topics')){
            foreach ($request->input('topics') as $topic_name) {
                if(!is_null($topic_name)){
                    Topic::create(['name' => $topic_name, 'subject_id' => $request->input('subject_id')]);
                }
            }
        }
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully created']);
        
        return redirect(route('subjects.show', $request->input('subject_id')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function edit(Topic $topic)
    {
        return view('web.topics.edit', ['item' => $topic]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        $topic->name = $request->input('name');
        $topic->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully edited']);
        
        return redirect(route('subjects.show', $topic->subject->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Topic $topic)
    {
        $subject_id = $topic->subject->id;
        $topic->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully deleted']);
        
        return redirect(route('subjects.show', $subject_id));
    }

    public function get_topics_by_subject(Request $request)
    {
        $topics = Topic::where('subject_id', $request->get('subject_id'))->get();

        return response()->json($topics);
    }
}
