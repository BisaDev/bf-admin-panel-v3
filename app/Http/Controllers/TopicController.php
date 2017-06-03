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
            'name' => 'required|string',
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
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Topic $topic)
    {
        $topic->name = $request->input('name');
        $topic->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully edited']);
        
        return redirect(route('subjects.show', $topic->subject->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Topic $topic)
    {
        $subject_id = $topic->subject->id;
        $topic->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully deleted']);
        
        return redirect(route('subjects.show', $subject_id));
    }
}