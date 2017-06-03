@section('page_title', 'Edit topic')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
            [ 'label' => 'Grade level details', 'url' => route('grade_levels.show', $item->subject->grade_level->id)],
            [ 'label' => 'Subject details', 'url' => route('subjects.show', $item->subject->id)],
        ],
        'currentSection' => 'Edit topic',
    ])
@endsection

@section('content')

    <div class="row" >
        <div class="col-md-6 col-md-offset-3">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('topics.update', $item->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Edit topic</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Topic Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
