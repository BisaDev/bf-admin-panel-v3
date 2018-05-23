@section('page_title', 'Edit employee')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit employee',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Employees', 'url' => route('employees.index')]
        ],
        'currentSection' => 'Edit employee',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container">
        <form action="{{ route('employees.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            
            <div class="col-lg-3">
                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="{{ $item->user_detail->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                        </div>
                        <div class="col-sm-12"><hr/></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1 {{ $errors->has('photo')? 'has-error' : '' }}">
                                    <div class="droppable">
                                        <span v-if="!photo">Drag an image or click to browse</span>
                                        <img v-else :src="photo" />
                                        <input name="photo" type="file" @change="createImage">
                                    </div>
                                    @if($errors->has('photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card-box">
                    @if($logged_user->id != $item->id)
                    <div class="row">
                        <div class="form-group col-md-12 {{ $errors->has('role')? 'has-error' : '' }}">
                            <label class="control-label" for="role">Role:</label>
                            <select name="role" class="form-control">
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ (old('role', $item->roles->first()->name) == $role->name)? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('role'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('role') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="form-group col-lg-3 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 {{ $errors->has('middle_name')? 'has-error' : '' }}">
                            <label class="control-label" for="middle_name">Middle Name:</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $item->middle_name) }}">
                            @if($errors->has('middle_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    
                        <div class="form-group col-lg-6 {{ $errors->has('last_name')? 'has-error' : '' }}">
                            <label class="control-label" for="last_name">Last Name:</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $item->last_name) }}">
                            @if($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-12 {{ $errors->has('email')? 'has-error' : '' }}">
                            <label class="control-label" for="email">Email:</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $item->email) }}">
                            @if($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($logged_user->id == $item->id)
                    <div class="row">
                        <div class="form-group col-lg-6 {{ $errors->has('password')? 'has-error' : '' }}">
                            <label class="control-label" for="password">Password:</label>
                            <input type="password" name="password" class="form-control">
                            @if($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-6 {{ $errors->has('password_confirmation')? 'has-error' : '' }}">
                            <label class="control-label" for="password_confirmation">Confirm Password:</label>
                            <input type="password" name="password_confirmation" class="form-control">
                            @if($errors->has('password_confirmation'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="form-group col-lg-6 {{ $errors->has('phone')? 'has-error' : '' }}">
                            <label class="control-label" for="phone">Phone Number:</label>
                            <input type="text" name="phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('phone', $item->user_detail->phone) }}">
                            @if($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-6 {{ $errors->has('mobile_phone')? 'has-error' : '' }}">
                            <label class="control-label" for="mobile_phone">Mobile Phone:</label>
                            <input type="text" name="mobile_phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('mobile_phone', $item->user_detail->mobile_phone) }}">
                            @if($errors->has('mobile_phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('mobile_phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 {{ $errors->has('secondary_email')? 'has-error' : '' }}">
                            <label class="control-label" for="secondary_email">Secondary Email:</label>
                            <input type="email" name="secondary_email" class="form-control" value="{{ old('secondary_email', $item->user_detail->secondary_email) }}">
                            @if($errors->has('secondary_email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('secondary_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 {{ $errors->has('location')? 'has-error' : '' }}">
                            <label class="control-label" for="location">Location:</label>
                            <select name="location" class="form-control">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (!is_null($item->user_detail->location) && old('location', $item->user_detail->location->id) == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
