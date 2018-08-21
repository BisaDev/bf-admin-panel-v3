@extends('layouts.master')

@section('page_title', 'Edit Exam Section')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit Exam ' . $exam->test_id . ', Section: ' . $examAnswers->first()->section_number,
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
        <form action="{{ route('exams.section.update', [$exam->id, $examAnswers->first()->section_number]) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-11 card-box">
                <div class="row">
                    <student-answer-sheet :questions="{{$sections['questions']}}" :answers="{{$examAnswers}}"></student-answer-sheet>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                    <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
