<?php

namespace Brightfox\Http\Controllers;

use Brightfox\Models\User, Brightfox\Models\UserDetail, Brightfox\Models\Location;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Brightfox\Traits\CreatesAndSavesPhotos;
use File;

class UserController extends Controller
{
    use CreatesAndSavesPhotos;
    
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::with('user_detail');
    
        /* TABLE FILTERS */
        $filters = [
            'location' => $request->input('location'),
            'role' => $request->input('role'),
        ];
        
        if($request->has('search')){
            $search = $request->input('search');
            $query->search($search);
        }
    
        if(!is_null($filters['location'])){
            $query->whereHas('user_detail', function ($subquery)use($filters) {
                $subquery->where('location_id', $filters['location']);
            });
        }
    
        if(!is_null($filters['role'])){
            $query->role($filters['role']);
        }else{
            $query->role(['Director', 'Instructor']);
        }
    
        $list = $query->paginate(50);
    
        $locations = Location::all();
        $roles = Role::where('name', '!=', 'admin')->get();

        return view('web.users.index', compact('list', 'search', 'filters', 'locations', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('web.users.create', [
            'roles' => Role::whereNotIn('name', ['admin'])->get(),
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
            'role' => 'required',
            'name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|unique:users|email',
            'email_confirmation' => 'same:email',
            'password' => 'required',
            'password_confirmation' => 'same:password',
            'secondary_email' => 'nullable|email',
            'photo' => 'nullable|image',
            'location' => 'required'
        ]);
        
        $user = User::create([
            'name' => $request->input('name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'password'  => bcrypt($request->input('password'))
        ]);

        $user_detail = UserDetail::create([
            'secondary_email' => $request->input('secondary_email'),
            'phone' => $request->input('phone'),
            'mobile_phone' => $request->input('mobile_phone'),
            'location_id' => $request->input('location'),
            'user_id' => $user->id
        ]);

        $user->assignRole($request->input('role'));
        $user->save();

        if ($request->hasFile('photo')) {
            $user_detail->photo = $this->createAndSavePhoto($request->file('photo'), UserDetail::PHOTO_PATH);
            $user_detail->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Employee was successfully created']);
        
        return redirect(route('employees.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Brightfox\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $employee)
    {
        return view('web.users.show', ['item' => $employee]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Brightfox\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $employee)
    {
        return view('web.users.edit', [
            'item' => $employee,
            'roles' => Role::whereNotIn('name', ['admin'])->get(),
            'locations' => Location::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Brightfox\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $employee)
    {
        $this->validate($request, [
            'role' => 'sometimes|required',
            'name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|unique:users,email,'.$employee->id.'|email',
            'secondary_email' => 'nullable|email',
            'password' => 'sometimes|nullable',
            'password_confirmation' => 'sometimes|same:password',
            'photo' => 'nullable|image',
            'location' => 'required'
        ]);
        
        
        $employee->name = $request->input('name');
        $employee->middle_name = $request->input('middle_name');
        $employee->last_name = $request->input('last_name');
        $employee->email = $request->input('email');
        if($request->has('password')){
            $employee->password = bcrypt($request->input('password'));
        }

        $employee->user_detail->secondary_email = $request->input('secondary_email');
        $employee->user_detail->phone = $request->input('phone');
        $employee->user_detail->mobile_phone = $request->input('mobile_phone');
        $employee->user_detail->location_id = $request->input('location');
        $employee->user_detail->save();

        if($request->has('role')){
            $employee->syncRoles([$request->input('role')]);
        }
        $employee->save();

        if ($request->hasFile('photo')) {
            if(!is_null($employee->user_detail->getOriginal('photo')) || $employee->user_detail->getOriginal('photo') != ''){
                File::delete(public_path(UserDetail::PHOTO_PATH . $employee->user_detail->getOriginal('photo')));
            }
            
            $employee->user_detail->photo = $this->createAndSavePhoto($request->file('photo'), UserDetail::PHOTO_PATH);
            $employee->user_detail->save();
        }

        if($employee->id == auth()->user()->id){
            $request->session()->flash('msg', ['type' => 'success', 'text' => 'Your profile was successfully edited']);
        }else{
            $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Employee was successfully edited']);
        }
        
        return redirect(route('employees.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Brightfox\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $employee)
    {
        $employee->removeRole($employee->roles->first()->name);
        $employee->user_detail->delete();
        $employee->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Employee was successfully deleted']);
        
        return redirect(route('employees.index'));
    }

    public function get_employees_by_location(Request $request)
    {
        $users = User::whereHas('user_detail', function ($query)use($request) {
            $query->where('location_id', $request->get('location_id'));
        })->get();

        foreach ($users as $employee) {
            if($employee->hasAnyRole(['instructor', 'director'])) {
                $employees[] = $employee;
            }
        }

        return response()->json($employees);
    }
}
