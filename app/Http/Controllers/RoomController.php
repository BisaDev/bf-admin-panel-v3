<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Room, Brightfox\Location;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $location_id
     * @return \Illuminate\Http\Response
     */
    public function create($location_id)
    {
        $location = Location::find($location_id);

        return view('web.rooms.create', compact('location'));
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
            'location_id' => 'required|numeric',
        ]);
        
        Room::create($request->only(['name', 'location_id']));

        if($request->has('rooms')){
            foreach ($request->input('rooms') as $room_name) {
                if(!is_null($room_name)){
                    Room::create(['name' => $room_name, 'location_id' => $request->input('location_id')]);
                }
            }
        }
        
        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Room was successfully created']);
        
        return redirect(route('locations.show', $request->input('location_id')));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        return view('web.rooms.edit', ['item' => $room]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        $room->name = $request->input('name');
        $room->save();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Room was successfully edited']);
        
        return redirect(route('locations.show', $room->location->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Room $room)
    {
        $location_id = $room->location->id;
        $room->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Room was successfully deleted']);
        
        return redirect(route('locations.show', $location_id));
    }
}
