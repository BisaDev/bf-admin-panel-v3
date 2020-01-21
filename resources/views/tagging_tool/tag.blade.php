@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Tagging Tool',
        'link' => [ 'label' => 'Upload image', 'url' => route('image-upload.index')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Tagging Tool', 'url' =>  route('taggingtool')]
        ],
        'currentSection' => $subject->name,
    ])
@endsection

@section('content')
    <div id="app">
        <tagging-tool
                tagging_route='{{ route('taggingtool.set_topic') }}'
                questions_route='{{ route('taggingquestion.list') }}'
                subject_id="{{$subject->id}}"
        ></tagging-tool>
    </div>
@endsection
