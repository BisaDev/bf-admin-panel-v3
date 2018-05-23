@section('page_title', 'Create student')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create student',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')]
        ],
        'currentSection' => 'Create student',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container">
        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-lg-3">
                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="{{ asset('images/empty-user.jpg') }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
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
                    <div class="row">
                        <div class="form-group col-lg-3 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-3 {{ $errors->has('middle_name')? 'has-error' : '' }}">
                            <label class="control-label" for="middle_name">Middle Name:</label>
                            <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                            @if($errors->has('middle_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('middle_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    
                        <div class="form-group col-lg-6 {{ $errors->has('last_name')? 'has-error' : '' }}">
                            <label class="control-label" for="last_name">Last Name:</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                            @if($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 {{ $errors->has('birthdate')? 'has-error' : '' }}">
                            <label class="control-label" for="birthdate">Birthdate:</label>
                            <input type="text" name="birthdate" class="form-control datepicker-birthday" value="{{ old('birthdate') }}">
                            @if($errors->has('birthdate'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('birthdate') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-6 {{ $errors->has('gender')? 'has-error' : '' }}">
                            <label class="control-label" for="gender">Identify as:</label>
                            <select name="gender" class="form-control">
                                @foreach($genders as $key => $gender)
                                <option value="{{ $key }}" {{ (!is_null(old('gender')) && (int)old('gender') === $key)? 'selected' : '' }}>{{ $gender }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gender'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('gender') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-md-2 {{ $errors->has('school_year')? 'has-error' : '' }}">
                            <label class="control-label" for="school_year">School Year:</label>
                            <input type="text" name="school_year" class="form-control" value="{{ old('school_year') }}">
                            @if($errors->has('school_year'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_year') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-5 {{ $errors->has('current_school')? 'has-error' : '' }}">
                            <label class="control-label" for="current_school">Current School:</label>
                            <input type="text" name="current_school" class="form-control" value="{{ old('current_school') }}">
                            @if($errors->has('current_school'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_school') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-5 {{ $errors->has('teacher')? 'has-error' : '' }}">
                            <label class="control-label" for="teacher">Teacher:</label>
                            <input type="text" name="teacher" class="form-control" value="{{ old('teacher') }}">
                            @if($errors->has('teacher'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('teacher') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 {{ $errors->has('former_school')? 'has-error' : '' }}">
                            <label class="control-label" for="former_school">Former School:</label>
                            <input type="text" name="former_school" class="form-control" value="{{ old('former_school') }}">
                            @if($errors->has('former_school'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('former_school') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 {{ $errors->has('location')? 'has-error' : '' }}">
                            <label class="control-label" for="location">Location:</label>
                            <select name="location" class="form-control">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (old('location') == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
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
                        @include('partials.add-notes', ['help_text' => 'Notes can be used to add academic notes, skills, health conditions, fun facts...'])
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('students.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
