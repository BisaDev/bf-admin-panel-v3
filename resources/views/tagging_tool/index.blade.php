@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Tagging Tool',
        'link' => [ 'label' => 'Upload image', 'url' => route('image-upload.index')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Tagging Tool',
    ])
@endsection

@section('content')
    <div id="app">
        <tag-stats
            :instructors='{{ json_encode($instructors) }}'
            :subjects='{{ json_encode($subjects) }}'></tag-stats>
    </div>
@endsection
