<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Minigame, Brightfox\Models\Note;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use File;

class MinigameController extends Controller
{
    use CreatesAndSavesPhotos;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = Minigame::paginate(50);

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
            'name' => 'required|string|max:191'
        ]);

        $minigame = Minigame::create($request->only(['name']));

        if ($request->hasFile('photo')) {
            $minigame->photo = $this->createAndSavePhoto($request->file('photo'), Minigame::PHOTO_PATH, 400, null);
            $minigame->save();
        }

        if($request->filled('notes')){
            foreach ($request->input('notes') as $note) {
                if(!is_null($note['title']) && !is_null($note['text'])){
                    $note = Note::create(['title' => $note['title'], 'text' => $note['text']]);
                    $minigame->notes()->save($note);
                }
            }
        }

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
        return view('web.minigames.show', ['item' => $minigame]);
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
        $this->validate($request, [
            'name' => 'required|string|max:191'
        ]);

        $minigame->name = $request->input('name');
        $minigame->save();

        if($request->hasFile('photo')) {
            if(!is_null($minigame->getOriginal('photo')) || $minigame->getOriginal('photo') != ''){
                File::delete(public_path(Minigame::PHOTO_PATH . $minigame->getOriginal('photo')));
            }

            $minigame->photo = $this->createAndSavePhoto($request->file('photo'), Minigame::PHOTO_PATH, 400, null);
            $minigame->save();
        }

        if($request->filled('notes')){
            $notes_ids = collect($request->get('notes'))->map(function($note){
                return $note['id'];
            })->toArray();

            $notes_to_delete = $minigame->notes()->whereNotIn('id', $notes_ids)->delete();

            foreach ($request->input('notes') as $key => $request_note) {
                if(!is_null($request_note['id'])){

                    $note = Note::find($request_note['id']);
                    $note->title = $request_note['title'];
                    $note->text = $request_note['text'];

                    $note->save();
                }else{
                    $note = Note::create(['title' => $request_note['title'], 'text' => $request_note['text']]);
                    $minigame->notes()->save($note);
                }
            }
        }else{
            $minigame->notes()->delete();
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
        $minigame->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Minigame was successfully deleted']);

        return redirect(route('minigames.index'));
    }
}
