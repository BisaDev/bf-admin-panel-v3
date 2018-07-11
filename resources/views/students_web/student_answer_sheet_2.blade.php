@extends('layouts.student_master')

@section('page_title', 'Section 2: Writing and Language')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Section 2: Writing and Language',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Section 2: Writing and Language',
    ])
@endsection

@section('content')

    <div id="app">
        <div class="col-md-12 text-right">
            <div class="h4 col-md-10 col-md-offset-1">
                <chronometer :available-time="35"></chronometer>
            </div>
        </div>

        <div class="col-md-10 col-md-offset-1 card-box">
            <div class="row">
                <student-answer-sheet :questions="44"></student-answer-sheet>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-10 col-md-offset-1 text-right">
                <button type="submit" class="btn btn-md btn-info">Submit</button>
            </div>
        </div>
    </div>

@endsection
