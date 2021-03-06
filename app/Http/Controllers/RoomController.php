<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Room, Brightfox\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'name' => [
                'required',
                'string',
                'max:191',
                Rule::unique('rooms')->where(function ($query)use($request) {
                    $query->where('location_id', $request->input('location_id'));
                })
            ],
            'location_id' => 'required|numeric',
            'rooms.*' => [
                'sometimes',
                'distinct',
                'max:191',
                Rule::unique('rooms')->where(function ($query)use($request) {
                    $query->where('location_id', $request->input('location_id'));
                })
            ]
        ]);

        Room::create($request->only(['name', 'location_id']));

        if($request->filled('rooms')){
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
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:rooms,name,'.$room->id.''
        ]);

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

    public function get_rooms_by_location(Request $request)
    {
        $rooms = Room::where('location_id', $request->get('location_id'))->get();

        return response()->json($rooms);
    }
}
