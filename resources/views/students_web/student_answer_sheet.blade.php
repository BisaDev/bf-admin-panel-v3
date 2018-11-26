@extends('layouts.student_master')
@section('page_title', 'Section: ' . $examMetadata->section_name)

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Section ' . $examMetadata->section_number . ': ' . $examMetadata->section_name,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Section ' . $examMetadata->section_number . ': ' . $examMetadata->section_name,
    ])
@endsection

@section('content')

    <div id="app">
        <form action="{{ route('answer_sheet.save_answers', $studentExamSection->id) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-12 text-right">
                <div class="h4 col-md-10 col-md-offset-1">
                    <chronometer :available-time=" {{ $examMetadata->time_available }}"></chronometer>
                </div>
            </div>

            @if($examMetadata->exam_type === 'SAT')
                <div class="col-md-10 col-md-offset-1 card-box">
                    <div class="row">
                        <student-answer-sheet :questions="{{ $examMetadata->questions - $examMetadata->open_questions}}" :answers="[]"></student-answer-sheet>
                    </div>
                </div>
                @if($examMetadata->section_number === 3 || $examMetadata->section_number === 4)
                    <div class="col-md-10 col-md-offset-1 card-box">
                        <div class="row">
                            <div class="container col-md-offset-1">
                                <div class="row text-center">
                                    @for ($i = $examMetadata->questions - $examMetadata->open_questions + 1; $i <= $examMetadata->questions; $i++)
                                        @if($i%5 == 0)
                                            <div class="row text-center">
                                                <student-answer-sheet-3 :num="{{$i}}"></student-answer-sheet-3>
                                            </div>
                                        @else
                                            <student-answer-sheet-3 :num="{{$i}}"></student-answer-sheet-3>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="{{ $examMetadata->section_number === 2 ? 'col-md-11' : 'col-md-10 col-md-offset-1' }} card-box">
                    <div class="row">
                        <act-answer-sheet :questions="{{ $examMetadata->questions }}" :section="{{ $studentExamSection->section_number }}" :answers="[]"></act-answer-sheet>
                    </div>
                </div>
            @endif


            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                   <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection
