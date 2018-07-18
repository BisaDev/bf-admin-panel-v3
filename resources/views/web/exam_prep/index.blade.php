@extends('layouts.master')

@section('page_title', 'Exams')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Exams',
        'link' => [ 'label' => 'Create Exam', 'url' => route('exams.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
        ],
        'currentSection' => 'Exams',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @foreach($examList as $exam)
                <ul>
                    <li>{{$exam->test_id}}</li>
                </ul>
            @endforeach
        </div>
    </div>
@endsection
