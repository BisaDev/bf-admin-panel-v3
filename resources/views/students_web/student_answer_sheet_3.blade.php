@extends('layouts.student_master')
@section('page_title', 'Section 3: Math-No Calculator')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Section 3: Math-No Calculator',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Section 3: Math-No Calculator',
    ])
@endsection

@section('content')

    <div id="app">
        <div class="col-md-12 text-right">
            <div class="h4 col-md-9 col-md-offset-1">
                <chronometer :available-time="25"></chronometer>
            </div>
        </div>

        <div class="col-md-9 col-md-offset-1 card-box">
            <div class="row">
                <student-answer-sheet :questions="15"></student-answer-sheet>
            </div>
        </div>

        <div class="col-md-9 col-md-offset-1 card-box">
            <div class="row">
                <student-answer-sheet-3></student-answer-sheet-3>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-md-9 col-md-offset-1 text-right">
                <button type="submit" class="btn btn-md btn-info">Submit</button>
            </div>
        </div>
    </div>

@endsection
