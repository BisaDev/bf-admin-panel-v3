@section('page_title', 'Tagging Tool - Image upload')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Image upload',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Image upload',
    ])
@endsection

@section('content')
    <div id="app" class="container">
        <down-wrapper
                topics_route="{{ route('taggingtopics.list') }}"
                :subjects="{{ json_encode($subjects) }}"
        ></down-wrapper>
    </div>
@endsection