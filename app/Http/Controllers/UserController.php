<?php

namespace Brightfox\Http\Controllers;

use Brightfox\User, Brightfox\UserDetail, Brightfox\Location;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Image;

class UserController extends Controller
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
            $list = User::role(['director', 'instructor'])->search($search)->paginate(10);
        }else{
            $list = User::role(['director', 'instructor'])->paginate(10);
        }

        return view('web.users.index', compact('list', 'search'));
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
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users|email',
            'confirm_email' => 'same:email',
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
            'password'  => bcrypt($request->input('middle_name'))
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
            $image = $request->file('photo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(512, 512)->save(public_path(UserDetail::PROFILE_PATH . $filename));
            $user_detail->photo = $filename;
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
        return view('web.users.create', ['item' => $employee]);
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
            'role' => 'required',
            'name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|unique:users,email,'.$employee->id.'|email',
            'secondary_email' => 'nullable|email',
            'photo' => 'nullable|image',
            'location' => 'required'
        ]);
        
        
        $employee->name = $request->input('name');
        $employee->middle_name = $request->input('middle_name');
        $employee->last_name = $request->input('last_name');
        $employee->email = $request->input('email');

        $employee->user_detail->secondary_email = $request->input('secondary_email');
        $employee->user_detail->phone = $request->input('phone');
        $employee->user_detail->mobile_phone = $request->input('mobile_phone');
        $employee->user_detail->location_id = $request->input('location');
        $employee->user_detail->save();

        $employee->syncRoles([$request->input('role')]);
        $employee->save();

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(512, 512)->save(public_path(UserDetail::PROFILE_PATH . $filename));
            $employee->user_detail->photo = $filename;
            $employee->user_detail->save();
        }

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Employee was successfully edited']);
        
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
        $employee->delete();

        $request->session()->flash('msg', ['type' => 'success', 'text' => 'The Employee was successfully deleted']);
        
        return redirect(route('employees.index'));
    }
}
