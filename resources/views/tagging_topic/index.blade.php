@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Grade Levels',
        'link' => [ 'label' => 'Create Topic', 'url' => route('students.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Tagging Tool Topics',
    ])
@endsection

@section('content')
    <div id="index-container" class="row">
        <div class="col-sm-12" id="app">
            <tag-topics></tag-topics>
        </div>
    </div>
@endsection