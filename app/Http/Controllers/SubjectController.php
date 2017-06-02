<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\GradeLevel;
use Brightfox\Subject;
use Brightfox\Topic;

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
            foreach ($request->input('topics') as $topic) {
                if(!is_null($topic)){
                    $topic = Topic::create(['name' => $topic, 'subject_id' => $subject->id]);
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
    public function show(Request $request, $id)
    {
        $item = Subject::find($id);

        if($request->has('search')){
            $search = $request->input('search');
            $item->topics = $item->topics()->where('name', 'like', '%'.$search.'%')->get();
        }

        return view('web.subjects.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Subject::find($id);

        return view('web.subjects.edit', compact('item'));
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
        $item = Subject::find($id);

        $item->name = $request->input('name');
        $item->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Subject was successfully edited']);
        
        return redirect(route('grade_levels.show', $item->grade_level->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $item = Subject::find($id);

        $grade_level_id = $item->grade_level->id;
        $item->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Subject was successfully deleted']);
        
        return redirect(route('grade_levels.show', $grade_level_id));
    }
}
