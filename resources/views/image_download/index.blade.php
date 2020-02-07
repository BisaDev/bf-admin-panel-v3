@section('page_title', 'Tagging Tool - Image upload')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Image download',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Image upload',
    ])
@endsection

@section('content')
    <div id="app" class="container">
        <down-wrapper
                download_route="{{ route('imagedownload.download') }}"
                question_route="{{route('imagedownload.question')}}"
                :subjects="{{ json_encode($subjects) }}"
        ></down-wrapper>
    </div>
@endsection