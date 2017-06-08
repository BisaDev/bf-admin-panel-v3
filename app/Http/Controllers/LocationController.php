<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('search')){
            $search = $request->input('search');
            $list = Location::search($search)->paginate(10);
        }else{
            $list = Location::paginate(10);
        }

        return view('web.locations.index', compact('list', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.locations.create');
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
            'email' => 'nullable|email'
        ]);
        
        Location::create($request->only(['name', 'address', 'city', 'state', 'phone', 'email']));

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Location was successfully created']);
        
        return redirect(route('locations.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Location $location)
    {
        $item = $location;

        if($request->has('search')){
            $search = $request->input('search');
            $item->rooms = $item->rooms()->search($search)->paginate(10);
        }else{
            $item->rooms = $item->rooms()->paginate(10);
        }

        return view('web.locations.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('web.locations.edit', ['item' => $location]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $location->name = $request->input('name');
        $location->address = $request->input('address');
        $location->city = $request->input('city');
        $location->state = $request->input('state');
        $location->phone = $request->input('phone');
        $location->email = $request->input('email');
        $location->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Location was successfully edited']);
        
        return redirect(route('locations.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Location $location)
    {
        $location->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Location was successfully deleted']);
        
        return redirect(route('locations.index'));
    }

    /**
     * Toggle resource's active column
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function toggle_active(Request $request, Location $location)
    {
        $location->active = $request->input('status');
        $location->save();
    }
}
