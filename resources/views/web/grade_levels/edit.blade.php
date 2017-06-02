@section('page_title', 'Edit grade level')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit grade level',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
        ],
        'currentSection' => 'Edit grade level',
    ])
@endsection

@section('content')

    <div class="row" >
        <div class="col-md-6 col-md-offset-3">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('grade_levels.update', $item->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Edit Grade Level</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Grade Level Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
