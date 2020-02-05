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
    <div id="app" class="container">
        @hasanyrole('director|admin')
            <down-wrapper
                    download_route="{{ route('imagedownload.download') }}"
                    question_route="{{route('imagedownload.question')}}"
                    :subjects="{{ json_encode($subject_topics) }}"
            ></down-wrapper>
        @else
            <tag-stats
                    tagging_route='{{ route('taggingtool.tag') }}'
                    :instructors='{{ json_encode($instructors) }}'
                    :subjects='{{ json_encode($subject_stats) }}'>
            </tag-stats>
        @endhasanyrole
    </div>
@endsection
