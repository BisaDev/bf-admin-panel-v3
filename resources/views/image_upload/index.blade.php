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
        <up-wrapper
                subject_url="{{route('taggingsubjects.subjects')}}"
                update_url="{{route('imageupload.upload')}}"
        >
        </up-wrapper>
    </div>
@endsection