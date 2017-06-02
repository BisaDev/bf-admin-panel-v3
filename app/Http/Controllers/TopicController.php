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
            foreach ($request->input('topics') as $topic) {
                if(!is_null($topic)){
                    $topic = Topic::create(['name' => $topic, 'subject_id' => $request->input('subject_id')]);
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
    public function edit($id)
    {
        $item = Topic::find($id);

        return view('web.topics.edit', compact('item'));
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
        $item = Topic::find($id);

        $item->name = $request->input('name');
        $item->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully edited']);
        
        return redirect(route('subjects.show', $item->subject->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $item = Topic::find($id);

        $subject = $item->subject->id;
        $item->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Topic was successfully deleted']);
        
        return redirect(route('subjects.show', $subject));
    }
}
