<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Minigame;
use Illuminate\Http\Request;

class MinigameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Minigame::paginate(10);
        
        return view('web.minigames.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.minigames.create');
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
        
        Minigame::create($request->only(['name']));
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully created']);
        
        return redirect(route('minigames.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Minigame  $minigame
     * @return \Illuminate\Http\Response
     */
    public function show(Minigame $minigame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Minigame  $minigame
     * @return \Illuminate\Http\Response
     */
    public function edit(Minigame $minigame)
    {
        return view('web.minigames.edit', ['item' => $minigame]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Minigame  $minigame
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Minigame $minigame)
    {
        $minigame->name = $request->input('name');
        $minigame->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully edited']);
        
        return redirect(route('minigames.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\Minigame  $minigame
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Minigame $minigame)
    {
        $minigame->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully deleted']);
        
        return redirect(route('minigames.index'));
    }
}
