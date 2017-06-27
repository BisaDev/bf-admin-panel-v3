<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\GradeLevel, Brightfox\Models\Subject;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = GradeLevel::paginate(50);
        
        return view('web.grade_levels.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.grade_levels.create');
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
            'name' => 'required|string|max:191'
        ]);
        
        $grade_level = GradeLevel::create($request->only(['name']));

        if($request->has('subjects')){
            foreach ($request->input('subjects') as $subject_name) {
                if(!is_null($subject_name)){
                    Subject::create(['name' => $subject_name, 'grade_level_id' => $grade_level->id]);
                }
            }
        }
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully created']);
        
        return redirect(route('grade_levels.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\GradeLevel  $grade_level
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, GradeLevel $grade_level)
    {
        $item = $grade_level;

        if($request->has('search')){
            $search = $request->input('search');
            $item->subjects = $item->subjects()->search($search)->with('topics')->paginate(50);
        }else{
            $item->subjects = $item->subjects()->with('topics')->paginate(50);
        }

        return view('web.grade_levels.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\GradeLevel  $grade_level
     * @return \Illuminate\Http\Response
     */
    public function edit(GradeLevel $grade_level)
    {
        return view('web.grade_levels.edit', ['item' => $grade_level]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\GradeLevel  $grade_level
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradeLevel $grade_level)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);
        
        $grade_level->name = $request->input('name');
        $grade_level->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully edited']);
        
        return redirect(route('grade_levels.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\GradeLevel  $grade_level
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, GradeLevel $grade_level)
    {
        $grade_level->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully deleted']);
        
        return redirect(route('grade_levels.index'));
    }
}
