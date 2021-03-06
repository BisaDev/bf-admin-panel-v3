@extends('layouts.student_master')

@section('page_title', 'Section 1: Reading Comprehension')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Section 1: Reading Comprehension',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Section 1: Reading Comprehension',
    ])
@endsection

@section('content')

    <div id="app">
        <form action="{{ route('answer_sheet.save_answers', $section) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-12 text-right">
                <div class="h4 col-md-10 col-md-offset-1">
                    <chronometer :available-time="65"></chronometer>
                </div>
            </div>

            <div class="col-md-11 card-box">
                <div class="row">
                    <student-answer-sheet :questions="52" :answers="[]"></student-answer-sheet>
                </div>
            </div>

            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                    <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>

        <div class="modal fade" id="modal-guessed" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    ...
                </div>
            </div>
        </div>

    </div>

@endsection
