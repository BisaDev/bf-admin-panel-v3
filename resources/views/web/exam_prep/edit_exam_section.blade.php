@extends('layouts.master')

@section('page_title', 'Edit Exam Section')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit Exam ' . $exam->test_id . ', Section: ' . $examQuestions->first()->section_number,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' =>  route('exams.index')],
            [ 'label' => $exam->test_id, 'url' =>  route('exams.show', $exam->id)],
        ],
        'currentSection' => 'Edit Exam Section'
    ])
@endsection

@section('content')
    <div class="row" id="app">
            <div class="col-md-11 card-box">

            </div>

    </div>
@endsection
