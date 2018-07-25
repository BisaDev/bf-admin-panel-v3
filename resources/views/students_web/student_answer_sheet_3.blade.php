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
            <div class="h4 col-md-10 col-md-offset-1">
                <chronometer :available-time="25"></chronometer>
            </div>
        </div>

        <form action="{{ route('answer_sheet.save_answers', $section) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-10 col-md-offset-1 card-box">
                <div class="row">
                    <student-answer-sheet :questions="15"></student-answer-sheet>
                </div>
            </div>

            <div class="col-md-10 col-md-offset-1 card-box">
                <div class="row">
                    <div class="container col-md-offset-1">
                        <div class="row text-center">
                            @for ($i = 16; $i <= 20; $i++)
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

            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                   <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>

@endsection
