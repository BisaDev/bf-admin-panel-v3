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

    <div class="row" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <form action="{{ route('employees.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('put') }}

                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Edit employee</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <img src="{{ $item->user_detail->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12"><hr/></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group col-md-6 col-md-offset-3 {{ $errors->has('photo')? 'has-error' : '' }}">
                                    <input name="photo" type="file" class="filestyle" >
                                    @if($errors->has('photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <option value="{{ $location->id }}" {{ (old('location', $item->user_detail->location->id) == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
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
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
