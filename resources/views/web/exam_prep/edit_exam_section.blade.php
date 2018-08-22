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
        <form action="{{ route('exams.section.update', [$exam->id, $examAnswers->first()->section_number]) }}"
              method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-11 card-box">
                <div class="row">
                    <student-answer-sheet :questions="{{$sections['questions'] - $sections['openQuestions']}}"
                                          :answers="{{$examAnswers}}"></student-answer-sheet>
                </div>

                @if($sections['id'] == 3 || $sections['id'] == 4 )
                    @for($i = $sections['questions'] - $sections['openQuestions'] + 1; $i <= $sections['questions']; $i++)
                        <div class="col-md-12 col-md-offset-1 question">
                            <div class="row question-number">{{$i}}</div>
                            @for($j = 1; $j <= 9; $j++)
                                <div class="col-md-1 edit">
                                    @if($i == $sections['questions'] - $sections['openQuestions'] + 1)
                                        <label class="label-edit">Correct {{$j}}</label>
                                    @endif
                                    <input class="form-control" type="text" name="question_{{$i}}[]"
                                           value="{{ old('question_'. $i[$j-1], $examAnswers[$i-1]['correct_'.$j]) }}"
                                           maxlength="4">
                                </div>
                            @endfor
                        </div>
                    @endfor
                @endif
            </div>

            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                    <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
