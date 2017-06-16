@section('page_title', 'Create topic')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
            [ 'label' => $subject->grade_level->name, 'url' => route('grade_levels.show', $subject->grade_level->id)],
            [ 'label' => $subject->name, 'url' => route('subjects.show', $subject->id)],
        ],
        'currentSection' => 'Create topic',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('topics.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Topic Name:</label>
                            <input type="text" name="name" class="form-control">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        @include('partials.add-topics')

                        <div class="form-group col-md-12 text-right">
                            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                            <a href="{{ route('subjects.show', $subject->id) }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
