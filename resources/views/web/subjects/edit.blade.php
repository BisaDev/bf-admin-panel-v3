@section('page_title', 'Edit subject')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit subject',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
            [ 'label' => $item->grade_level->name, 'url' => route('grade_levels.show', $item->grade_level->id)],
        ],
        'currentSection' => 'Edit subject',
    ])
@endsection

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('subjects.update', $item->id) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Subject Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $item->name) }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('grade_levels.show', $item->grade_level->id) }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
