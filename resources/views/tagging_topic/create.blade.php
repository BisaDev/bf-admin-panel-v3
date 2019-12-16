@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Grade Levels',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Topics', 'url' =>  route('students.create')]
        ],
        'currentSection' => 'Create Tagging Topic',
    ])
@endsection

@section('content')
    <div id="index-container" class="row">
        <div class="col-sm-12" id="app">
            <tag-topics-create></tag-topics-create>
        </div>
    </div>
@endsection