<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Subject, Brightfox\Topic, Brightfox\GradeLevel;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($grade_level_id)
    {
        $grade_level = GradeLevel::find($grade_level_id);

        return view('web.subjects.create', compact('grade_level'));
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
            'grade_level_id' => 'required|numeric',
        ]);
        
        $subject = Subject::create($request->only(['name', 'grade_level_id']));

        if($request->has('topics')){
            foreach ($request->input('topics') as $topic_name) {
                if(!is_null($topic_name)){
                    Topic::create(['name' => $topic_name, 'subject_id' => $subject->id]);
                }
            }
        }
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Subject was successfully created']);
        
        return redirect(route('grade_levels.show', $request->input('grade_level_id')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subject $subject)
    {
        $item = $subject;

        if($request->has('search')){
            $search = $request->input('search');
            $item->topics = $item->topics()->search($search)->paginate(10);
        }else{
            $item->topics = $item->topics()->paginate(10);
        }

        return view('web.subjects.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        return view('web.subjects.edit', ['item' => $subject]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $subject->name = $request->input('name');
        $subject->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Subject was successfully edited']);
        
        return redirect(route('grade_levels.show', $subject->grade_level->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Subject $subject)
    {
        $grade_level_id = $subject->grade_level->id;
        $subject->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Subject was successfully deleted']);
        
        return redirect(route('grade_levels.show', $grade_level_id));
    }
}