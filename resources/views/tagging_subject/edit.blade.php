@section('page_title', 'Edit subject')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Subject',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Subjects', 'url' => route('taggingsubjects.index')],
        ],
        'currentSection' => 'Edit subject'
    ])
@endsection
@section('content')
    <div data-model="topic" class="row">
        <form action="{{ route('taggingsubjects.update', $subject->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="col-md-8 col-md-offset-2 card-box">
                <div class="form-group">
                    <label class="control-label" for="name">Subject Name:</label>
                    <input type="text" name="name" class="form-control" value="{{ $subject->name }}">
                </div>
                <div class="form-group col-md-12 text-right">
                    <a href="{{route('taggingsubjects.index')}}" class="btn btn-md btn-info">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>

@endsection
