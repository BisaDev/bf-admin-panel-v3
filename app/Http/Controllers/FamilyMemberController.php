<?php

namespace Brightfox\Http\Controllers;

use Brightfox\FamilyMember, Brightfox\Student;
use Illuminate\Http\Request;
use File;

class FamilyMemberController extends Controller
{
    protected $types = FamilyMember::TYPES;

    /**
     * Show the form for creating a new resource.
     *
     * @param  int  $student_id
     * @return \Illuminate\Http\Response
     */
    public function create($student_id)
    {
        $student = Student::find($student_id);
        $types = $this->types;

        return view('web.family_members.create', compact('student', 'types'));
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
            'email' => 'required|email',
            'email_confirmation' => 'same:email',
            'secondary_email' => 'nullable|email',
            'photo' => 'nullable|image',
        ]);
        
        $family_member = FamilyMember::create([
            'name' => $request->input('name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'secondary_email' => $request->input('secondary_email'),
            'phone' => $request->input('phone'),
            'mobile_phone' => $request->input('mobile_phone'),
            'type' => json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'student_id' => $request->input('student_id'),
            'can_pickup' => ($request->has('can_pickup'))? 1 : 0
        ]);

        if ($request->hasFile('photo')) {
            $family_member->photo = $this->createAndSavePhoto($request->file('photo'), FamilyMember::PHOTO_PATH);
            $family_member->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Family Member was successfully created']);
        
        return redirect(route('students.show', $request->input('student_id')));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\FamilyMember  $family_member
     * @return \Illuminate\Http\Response
     */
    public function show(FamilyMember $family_member)
    {
        return view('web.family_members.show', ['item' => $family_member]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\FamilyMember  $family_member
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyMember $family_member)
    {
        return view('web.family_members.edit', ['item' => $family_member, 'types' => $this->types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\FamilyMember  $family_member
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FamilyMember $family_member)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'secondary_email' => 'nullable|email',
            'photo' => 'nullable|image',
        ]);
        
        $family_member->name = $request->input('name');
        $family_member->middle_name = $request->input('middle_name');
        $family_member->last_name = $request->input('last_name');
        $family_member->email = $request->input('email');
        $family_member->secondary_email = $request->input('secondary_email');
        $family_member->phone = $request->input('phone');
        $family_member->mobile_phone = $request->input('mobile_phone');
        $family_member->type = json_encode(['key' => $request->input('type'), 'name' => $this->types[$request->input('type')]], JSON_FORCE_OBJECT);
        $family_member->address = $request->input('address');
        $family_member->city = $request->input('city');
        $family_member->state = $request->input('state');
        $family_member->can_pickup = ($request->has('can_pickup'))? 1 : 0;
        $family_member->save();

        if ($request->hasFile('photo')) {
            if(!is_null($family_member->getOriginal('photo')) || $family_member->getOriginal('photo') != ''){
                File::delete(public_path(FamilyMember::PHOTO_PATH . $family_member->getOriginal('photo')));
            }

            $family_member->photo = $this->createAndSavePhoto($request->file('photo'), FamilyMember::PHOTO_PATH);
            $family_member->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Family Member was successfully edited']);
        
        return redirect(route('students.show', $family_member->student->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\FamilyMember  $family_member
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FamilyMember $family_member)
    {
        $student_id = $family_member->student->id;
        $family_member->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Family Member was successfully deleted']);
        
        return redirect(route('students.show', $student_id));
    }

    /**
     * Toggle resource's active column
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\FamilyMember  $location
     * @return \Illuminate\Http\Response
     */
    public function toggle_active(Request $request, FamilyMember $family_member)
    {
        $family_member->active = $request->input('status');
        $family_member->save();
    }

    /**
     * Toggle resource's active column
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\FamilyMember  $family_member
     * @return \Illuminate\Http\Response
     */
    public function toggle_pickup(Request $request, FamilyMember $family_member)
    {
        $family_member->can_pickup = $request->input('status');
        $family_member->save();
    }
}
