@section('page_title', 'Create location')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create location',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Facilities', 'url' => route('locations.index')]
        ],
        'currentSection' => 'Create location',
    ])
@endsection

@section('content')

    <div class="row" >
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('locations.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Create a new location</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Location Name:</label>
                            <input type="text" name="name" class="form-control">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('address')? 'has-error' : '' }}">
                            <label class="control-label" for="address">Address:</label>
                            <textarea class="form-control" name="address"></textarea>
                            @if($errors->has('address'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('city')? 'has-error' : '' }}">
                            <label class="control-label" for="city">City:</label>
                            <input type="text" name="city" class="form-control">
                            @if($errors->has('city'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('state')? 'has-error' : '' }}">
                            <label class="control-label" for="state">State:</label>
                            <input type="text" name="state" class="form-control">
                            @if($errors->has('state'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('state') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('phone')? 'has-error' : '' }}">
                            <label class="control-label" for="phone">Phone Number:</label>
                            <input type="text" name="phone" data-mask="(999) 999-9999" class="form-control">
                            @if($errors->has('phone'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-6 {{ $errors->has('email')? 'has-error' : '' }}">
                            <label class="control-label" for="email">Contact Email:</label>
                            <input type="email" name="email" class="form-control">
                            @if($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
