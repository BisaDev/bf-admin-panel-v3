<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Student, Brightfox\Location, Brightfox\Note;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use Carbon\Carbon;
use File;

class StudentController extends Controller
{
    use CreatesAndSavesPhotos;
    protected $genders = Student::GENDERS;

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
            $list = Student::search($search)->paginate(10);
        }else{
            $list = Student::paginate(10);
        }

        return view('web.students.index', compact('list', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.students.create', [
            'locations' => Location::all(),
            'genders' => $this->genders
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
            'name' => 'required|string',
            'last_name' => 'required|string',
            'photo' => 'nullable|image',
            'birthdate' => 'date_format:m/d/Y',
            'location' => 'required'
        ]);
        
        if($request->input('birthdate')){
            $birthdate = Carbon::createFromFormat('m/d/Y', $request->input('birthdate'));
            $birthdate_format = $birthdate->toDateString();
        }else{
            $birthdate_format = "";
        }

        $student = Student::create([
            'name' => $request->input('name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthdate' => $birthdate_format,
            'gender' => json_encode(['key' => $request->input('gender'), 'name' => $this->genders[$request->input('gender')]], JSON_FORCE_OBJECT),
            'school_year' => $request->input('school_year'),
            'current_school' => $request->input('current_school'),
            'teacher' => $request->input('teacher'),
            'former_school' => $request->input('former_school'),
            'location_id' => $request->input('location')
        ]);

        if ($request->hasFile('photo')) {
            $student->photo = $this->createAndSavePhoto($request->file('photo'), Student::PHOTO_PATH);
            $student->save();
        }

        if($request->has('notes')){
            foreach ($request->input('notes') as $note) {
                if(!is_null($note['title']) && !is_null($note['text'])){
                    $note = Note::create(['title' => $note['title'], 'text' => $note['text']]);
                    $student->notes()->save($note);
                }
            }
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully created']);
        
        return redirect(route('students.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Student $student)
    {
        $item = $student;

        if($request->has('search')){
            $search = $request->input('search');
            $item->family_members = $item->family_members()->search($search)->paginate(10);
        }else{
            $item->family_members = $item->family_members()->paginate(10);
        }

        return view('web.students.show', compact('item', 'search'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        return view('web.students.edit', [
            'item' => $student,
            'locations' => Location::all(),
            'genders' => $this->genders
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'photo' => 'nullable|image',
            'birthdate' => 'date_format:m/d/Y',
            'location' => 'required'
        ]);

        if($request->input('birthdate')){
            $birthdate = Carbon::createFromFormat('m/d/Y', $request->input('birthdate'));
            $birthdate_format = $birthdate->toDateString();
        }else{
            $birthdate_format = "";
        }
        
        $student->name = $request->input('name');
        $student->middle_name = $request->input('middle_name');
        $student->last_name = $request->input('last_name');
        $student->birthdate = $birthdate_format;
        $student->gender = json_encode(['key' => $request->input('gender'), 'name' => $this->genders[$request->input('gender')]], JSON_FORCE_OBJECT);
        $student->school_year = $request->input('school_year');
        $student->current_school = $request->input('current_school');
        $student->teacher = $request->input('teacher');
        $student->former_school = $request->input('former_school');
        $student->location_id = $request->input('location');
        $student->save();

        if ($request->hasFile('photo')) {
            if(!is_null($student->getOriginal('photo')) || $student->getOriginal('photo') != ''){
                File::delete(public_path(Student::PHOTO_PATH . $student->getOriginal('photo')));
            }

            $student->photo = $this->createAndSavePhoto($request->file('photo'), Student::PHOTO_PATH);
            $student->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully edited']);
        
        return redirect(route('students.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Student $student)
    {
        $student->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Student was successfully deleted']);
        
        return redirect(route('students.index'));
    }
}
