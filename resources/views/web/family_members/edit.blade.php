@section('page_title', 'Edit family member')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit family member',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')],
            [ 'label' => $item->student->full_name, 'url' => route('students.show', $item->student->id)],
        ],
        'currentSection' => 'Edit family member',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz">
        <form action="{{ route('family_members.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}
            
            <div class="col-lg-3">
                <div class="card-box">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <img src="{{ $item->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                        </div>
                        <div class="col-sm-12"><hr/></div>
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="form-group col-md-10 col-md-offset-1 {{ $errors->has('photo')? 'has-error' : '' }}">
                                    <div class="droppable">
                                        <span v-if="!photo">Drag an image or click to browse</span>
                                        <img v-else :src="photo" />
                                        <input name="photo" type="file" @change="onFileChange">
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
                        <div class="form-group col-lg-6 {{ $errors->has('type')? 'has-error' : '' }}">
                            <label class="control-label" for="type">Type:</label>
                            <select name="type" class="form-control">
                                @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ (!is_null(old('type', $item->type->key)) && (int)old('type', $item->type->key) === $key)? 'selected' : '' }}>{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
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
                            <input type="text" name="phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('phone', $item->phone) }}">
                            @if($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-6 {{ $errors->has('mobile_phone')? 'has-error' : '' }}">
                            <label class="control-label" for="mobile_phone">Mobile Phone:</label>
                            <input type="text" name="mobile_phone" data-mask="(999) 999-9999" class="form-control" value="{{ old('mobile_phone', $item->mobile_phone) }}">
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
                            <input type="email" name="secondary_email" class="form-control" value="{{ old('secondary_email', $item->secondary_email) }}">
                            @if($errors->has('secondary_email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('secondary_email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 {{ $errors->has('address')? 'has-error' : '' }}">
                            <label class="control-label" for="address">Address:</label>
                            <textarea class="form-control" name="address">{{ old('address', $item->address) }}</textarea>
                            @if($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-lg-6 {{ $errors->has('city')? 'has-error' : '' }}">
                            <label class="control-label" for="city">City:</label>
                            <input type="text" name="city" class="form-control" value="{{ old('city', $item->city) }}">
                            @if($errors->has('city'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-lg-6 {{ $errors->has('state')? 'has-error' : '' }}">
                            <label class="control-label" for="state">State:</label>
                            <input type="text" name="state" class="form-control" value="{{ old('state', $item->state) }}">
                            @if($errors->has('state'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('state') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 {{ $errors->has('can_pickup')? 'has-error' : '' }}">
                            <div class="checkbox checkbox-primary">
                                <input type="checkbox" id="can_pickup" name="can_pickup" {{ old('can_pickup', $item->can_pickup) ? 'checked' : '' }}>
                                <label for="can_pickup">Can pick up?</label>
                            </div>
                            @if($errors->has('can_pickup'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('can_pickup') }}</strong>
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
                </div>
            </div>
        </form>
    </div>

@endsection
