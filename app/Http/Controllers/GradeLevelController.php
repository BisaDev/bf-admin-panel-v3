<?php

namespace Brightfox\Http\Controllers;

use Illuminate\Http\Request;
use Brightfox\GradeLevel;

class GradeLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = GradeLevel::all();
        
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
            'name' => 'required|string'
        ]);
        
        GradeLevel::create($request->only(['name']));
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully created']);
        
        return redirect(route('grade_levels.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $item = GradeLevel::find($id);

        if($request->has('search')){
            $search = $request->input('search');
            $item->subjects = $item->subjects()->where('name', 'like', '%'.$search.'%')->get();
        }

        return view('web.grade_levels.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = GradeLevel::find($id);

        return view('web.grade_levels.edit', compact('item'));
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
        $item = GradeLevel::find($id);

        $item->name = $request->input('name');
        $item->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully edited']);
        
        return redirect(route('grade_levels.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $item = GradeLevel::find($id);

        $item->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Grade Level was successfully deleted']);
        
        return redirect(route('grade_levels.index'));
    }
}
