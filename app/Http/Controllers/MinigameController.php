<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Minigame;
use Illuminate\Http\Request;
use Image;

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
        
        $minigame = Minigame::create($request->only(['name']));

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path(Minigame::PROFILE_PATH . $filename));
            $minigame->photo = $filename;
            $minigame->save();
        }
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully created']);
        
        return redirect(route('minigames.index'));
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

        if ($request->hasFile('photo')) {
            if(!is_null($minigame->getOriginal('photo')) || $minigame->getOriginal('photo') != ''){
                unlink(public_path(Minigame::PROFILE_PATH . $minigame->getOriginal('photo')));
            }

            $image = $request->file('photo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path(Minigame::PROFILE_PATH . $filename));
            $minigame->photo = $filename;
            $minigame->save();
        }

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
        if(!is_null($minigame->getOriginal('photo')) || $minigame->getOriginal('photo') != ''){
            unlink(public_path(Minigame::PROFILE_PATH . $minigame->getOriginal('photo')));
        }

        $minigame->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully deleted']);
        
        return redirect(route('minigames.index'));
    }
}
