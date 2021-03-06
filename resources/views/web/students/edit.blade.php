@section('page_title', 'Edit student')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit student',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')]
        ],
        'currentSection' => 'Edit student',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container" data-notes="{{ $item->notes->toJson() }}">
        <form action="{{ route('students.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="col-lg-3">
                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="{{ $item->photo }}" class="img-responsive img-circle" alt="profile-image">
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
                        <div class="form-group col-lg-6 {{ $errors->has('birthdate')? 'has-error' : '' }}">
                            <label class="control-label" for="birthdate">Birthdate:</label>
                            <input type="text" name="birthdate" class="form-control datepicker-birthday" value="{{ old('birthdate', ($item->birthdate)? $item->birthdate->format('m/d/Y') : '')  }}">
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
                                <option value="{{ $key }}" {{ (!is_null(old('gender', $item->gender->key)) && (int)old('gender', $item->gender->key) === $key)? 'selected' : '' }}>{{ $gender }}</option>
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
                        <div class="form-group col-md-6 {{ $errors->has('school_year')? 'has-error' : '' }}">
                            <label class="control-label" for="school_year">School Year:</label>
                            <input type="text" name="school_year" class="form-control" value="{{ old('school_year', $item->school_year) }}">
                            @if($errors->has('school_year'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('school_year') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('current_school')? 'has-error' : '' }}">
                            <label class="control-label" for="current_school">Current School:</label>
                            <input type="text" name="current_school" class="form-control" value="{{ old('current_school', $item->current_school) }}">
                            @if($errors->has('current_school'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_school') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 {{ $errors->has('teacher')? 'has-error' : '' }}">
                            <label class="control-label" for="teacher">Teacher:</label>
                            <input type="text" name="teacher" class="form-control" value="{{ old('teacher', $item->teacher) }}">
                            @if($errors->has('teacher'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('teacher') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6 {{ $errors->has('cohort_tag')? 'has-error' : '' }}">
                            <label class="control-label" for="cohort_tag">Cohort:</label>
                            <input type="text" name="cohort_tag" class="form-control" value="{{ old('cohort_tag', $item->cohort_tag) }}">
                            @if($errors->has('cohort_tag'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cohort_tag') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6 {{ $errors->has('former_school')? 'has-error' : '' }}">
                            <label class="control-label" for="former_school">Former School:</label>
                            <input type="text" name="former_school" class="form-control" value="{{ old('former_school', $item->former_school) }}">
                            @if($errors->has('former_school'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('former_school') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('location')? 'has-error' : '' }}">
                            <label class="control-label" for="location">Location:</label>
                            <select name="location" class="form-control">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (old('location', $item->location->id) == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
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
                        <div class="form-group col-md-6 {{ $errors->has('current_classes')? 'has-error' : '' }}">
                            <label class="control-label" for="current_classes">Current Classes at School:</label>
                            <input type="text" name="current_classes" class="form-control" value="{{ old('current_classes', $item->current_classes) }}" maxlength="50">
                            @if($errors->has('current_classes'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('current_classes') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('previous_classes')? 'has-error' : '' }}">
                            <label class="control-label" for="previous_classes">Previous Classes at School:</label>
                            <input type="text" name="previous_classes" class="form-control" value="{{ old('previous_classes', $item->previous_classes) }}" maxlength="50">
                            @if($errors->has('previous_classes'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('previous_classes') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($item->user)
                        <div class="row">
                            <div class="form-group col-lg-6 {{ $errors->has('email')? 'has-error' : '' }}">
                                <label class="control-label" for="email">Email:</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $item->user->email) }}">
                                @if($errors->has('email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-6 {{ $errors->has('email_confirmation')? 'has-error' : '' }}">
                                <label class="control-label" for="email_confirmation">Confirm Email:</label>
                                <input type="email" name="email_confirmation" class="form-control" value="{{ old('email_confirmation', $item->user->email) }}">
                                @if($errors->has('email_confirmation'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('email_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

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

                        <div class="row">
                            <div class="form-group col-lg-6 {{ $errors->has('phone')? 'has-error' : '' }}">
                                <label class="control-label" for="phone">Phone Number:</label>
                                <input type="text" name="phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('phone', $item->user->user_detail->phone ) }}">
                                @if($errors->has('phone'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group col-lg-6 {{ $errors->has('mobile_phone')? 'has-error' : '' }}">
                                <label class="control-label" for="mobile_phone">Mobile Phone:</label>
                                <input type="text" name="mobile_phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('mobile_phone', $item->user->user_detail->mobile_phone) }}">
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
                                <input type="email" name="secondary_email" class="form-control" value="{{ old('secondary_email', $item->user->user_detail->secondary_email) }}">
                                @if($errors->has('secondary_email'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('secondary_email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                    @endif

                    <div class="row">
                        @include('partials.add-notes', ['help_text' => 'Notes can be used to add academic notes, skills, health conditions, fun facts...'])
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('students.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
