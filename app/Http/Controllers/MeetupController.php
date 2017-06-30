<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\Meetup, Brightfox\Models\ActivityBucket, Brightfox\Models\GradeLevel, Brightfox\Models\Location, Brightfox\Models\Student, Brightfox\Models\Note;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
class MeetupController extends Controller
{
    protected $status = Meetup::STATUS;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('go_to_week')){
            $start_of_week = Carbon::parse($request->get('go_to_week'))->startOfWeek()->startOfDay();
        }elseif($request->has('start_of_week')){
            $start_of_week = Carbon::parse($request->get('start_of_week'))->startOfDay();
        }else{
            $start_of_week = Carbon::today()->startOfWeek()->startOfDay();
        }

        $meetups_by_date = [];
        $end_of_week = $start_of_week->copy();
        while ($end_of_week->format('l') != 'Saturday') {
            $meetups_by_date[$end_of_week->format('Y-m-d')] = [
                'date' => $end_of_week->copy()
            ];

            $end_of_week->addDay();
        }

        $end_of_week->subDay()->endOfDay();

        $meetups = Meetup::where('start_time', '>=', $start_of_week)->where('end_time', '<=', $end_of_week)->orderBy('start_time', 'asc');

        $filters = [];
        if($request->has('location')){
            $filters['location'] = $request->input('location');

            $meetups->whereHas('room', function ($query)use($filters) {
                $query->where('location_id', $filters['location']);
            });
        }

        $meetups->get()->groupBy(function ($meetup) {
            return $meetup->start_time->toDateString();
        })->each(function($meetups, $date)use(&$meetups_by_date){
            $meetups_by_date[$date]['meetups'] = $meetups;
        });

        $locations = Location::all();
        $filter_string = http_build_query($filters);

        return view('web.meetups.index', compact('meetups_by_date', 'start_of_week', 'end_of_week', 'locations', 'filters', 'filter_string'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.meetups.create', [
            'grade_levels' => GradeLevel::all(),
            'locations' => Location::all()
        ]);
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
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required',
            'user' => 'required'
        ]);

        $start_time = Carbon::parse($request->input('date')." ".$request->input('start_time'));
        $end_time = Carbon::parse($request->input('date')." ".$request->input('end_time'));

        $meetup = Meetup::create([
            'start_time' => $start_time->toDateTimeString(),
            'end_time' => $end_time->toDateTimeString(),
            'room_id' => $request->input('room'),
            'user_id' => $request->input('user')
        ]);

        if($request->has('activity_bucket')){
            $activity_bucket = ActivityBucket::find($request->input('activity_bucket'));
            $meetup->activity_bucket()->associate($activity_bucket);
            $meetup->save();

            $redirect = route('meetups.attendance', $meetup->id);
        }else{
            $redirect = route('activity_buckets.create', $meetup->id);
        }

        $meetup->status = json_encode(['key' => '0', 'name' => 'Incomplete'], JSON_FORCE_OBJECT);
        $meetup->save();

        return redirect($redirect);
    }

    /**
     * Choose meetup attendance.
     *
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function attendance(Meetup $meetup)
    {
        $students = Student::where('location_id', $meetup->room->location->id)->get();

        return view('web.meetups.attendance', ['item' => $meetup, 'students' => $students]);
    }

    /**
     * Choose meetup attendance.
     *
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function attendance_store(Request $request, Meetup $meetup)
    {
        $meetup->students()->sync($request->input('students'));

        if(!is_null($meetup->activity_bucket)){
            $meetup->status = json_encode(['key' => '1', 'name' => 'Ready'], JSON_FORCE_OBJECT);
            $meetup->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Meetup was created succesfully']);
        return redirect(route('meetups.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function show(Meetup $meetup)
    {
        return view('web.meetups.show', ['item' => $meetup]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function edit(Meetup $meetup)
    {
        return view('web.meetups.edit', [
            'item' => $meetup,
            'grade_levels' => GradeLevel::all(),
            'locations' => Location::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meetup $meetup)
    {
        $this->validate($request, [
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required',
            'user' => 'required'
        ]);

        $start_time = Carbon::parse($request->input('date')." ".$request->input('start_time'));
        $end_time = Carbon::parse($request->input('date')." ".$request->input('end_time'));

        $meetup->start_time = $start_time->toDateTimeString();
        $meetup->end_time = $end_time->toDateTimeString();
        $meetup->room_id = $request->input('room');
        $meetup->user_id = $request->input('user');
        $meetup->save();

        if($request->has('activity_bucket')){
            $activity_bucket = ActivityBucket::find($request->input('activity_bucket'));
            $meetup->activity_bucket()->associate($activity_bucket);
            $meetup->save();

            $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Meetup was edited succesfully']);
            $redirect = route('meetups.index');
        }else{            
            $redirect = route('activity_buckets.create', $meetup->id);
        }

        return redirect($redirect);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\Models\Meetup  $meetup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meetup $meetup)
    {
        //
    }
    
    public function student_detail(Request $request, Meetup $meetup, Student $student)
    {
        if($request->has('notes')){
            $notes_ids = collect($request->get('notes'))->map(function($note){
                return $note['id'];
            })->toArray();
    
            $student->meetup_student_pivot($meetup->id)->first()->notes()->whereNotIn('id', $notes_ids)->delete();
        
            foreach ($request->input('notes') as $key => $request_note) {
                if(!is_null($request_note['id'])){
                
                    $note = Note::find($request_note['id']);
                    $note->title = $request_note['title'];
                    $note->text = $request_note['text'];
                
                    $note->save();
                }else{
                    $note = Note::create(['title' => $request_note['title'], 'text' => $request_note['text']]);
                    $student->meetup_student_pivot($meetup->id)->first()->notes()->save($note);
                }
            }
        }elseif($request->method() == 'POST'){
            $student->meetup_student_pivot($meetup->id)->first()->notes()->delete();
        }
        
        return view('web.meetups.student_detail', compact('meetup', 'student'));
    }
}
