@section('page_title', 'Create subject')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create subject',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
            [ 'label' => 'Grade level details', 'url' => route('grade_levels.show', $grade_level->id)],
        ],
        'currentSection' => 'Create subject',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('subjects.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Create a new subject for {{ $grade_level->name }} Grade Level</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Subject Name:</label>
                            <input type="text" name="name" class="form-control">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        @include('partials.add-topics')

                        <div class="form-group col-md-12 text-right m-t-30">
                            <input type="hidden" name="grade_level_id" value="{{ $grade_level->id }}">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
