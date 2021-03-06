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
            <tabs>
                <tab name="exam answers" :selected="true">
                    @if($exam->type === 'SAT')
                        <div class="col-md-11 card-box">
                            <div class="row">
                                <student-answer-sheet :questions="{{$section->questions - $section->open_questions}}"
                                                      :answers="{{$examAnswers}}"></student-answer-sheet>
                            </div>

                            @if($section->section_number == 3 || $section->section_number == 4 )
                                @for($i = $section->questions - $section->open_questions + 1; $i <= $section->questions; $i++)
                                    <div class="col-md-12 col-md-offset-1 question">
                                        <div class="row question-number">{{$i}}</div>
                                        @for($j = 1; $j <= 9; $j++)
                                            <div class="col-md-1 edit">
                                                @if($i == $section->questions - $section->open_questions + 1)
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
                    @elseif($exam->type === 'ACT')
                        <div class="col-md-11 card-box">
                            <div class="row">
                                <act-answer-sheet :questions="{{ $section->questions }}"
                                                  :section="{{ $section->section_number }}"
                                                  :answers="{{ $examAnswers }}"
                                                  :edit="{{true}}"></act-answer-sheet>
                            </div>
                        </div>
                    @elseif($exam->IsMiniExam)
                        @if($exam->mini_exam_format === 'mc-4-ABCD')
                            <div class="col-md-11 card-box">
                                <div class="row">
                                    <student-answer-sheet :questions="{{ $exam->mini_exam_questions }}"
                                                          :answers="{{ $examAnswers }}"></student-answer-sheet>
                                </div>
                            </div>
                        @elseif($exam->mini_exam_format === 'mc-4-FGHJ')
                            <div class="col-md-11 card-box">
                                <div class="row">
                                    <act-answer-sheet :questions="{{ $exam->mini_exam_questions }}" :section="1"
                                                      :answers="{{ $examAnswers }}"
                                                      :edit="{{true}}"></act-answer-sheet>
                                </div>
                            </div>
                        @elseif($exam->mini_exam_format === 'mc-5-FGHJ')
                            <div class="col-md-11 card-box">
                                <div class="row">
                                    <act-answer-sheet :questions="{{ $exam->mini_exam_questions }}" :section="2"
                                                      :answers="{{ $examAnswers }}"
                                                      :edit="{{true}}"></act-answer-sheet>
                                </div>
                            </div>
                        @elseif($exam->mini_exam_format === 'math-grid')
                            @for($i = 1; $i <= $exam->mini_exam_questions; $i++)
                                <div class="col-md-12 col-md-offset-1 question">
                                    <div class="row question-number">{{$i}}</div>
                                    @for($j = 1; $j <= 9; $j++)
                                        <div class="col-md-1 edit">
                                            @if($i === 1)
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
                    @endif
                </tab>
                <tab name="topics">
                    <div class="col-md-11 card-box">
                        <div class="col-md-11 card-box">
                            @foreach($examAnswers as $examAnswer)
                                <div class="row form-group">
                                    <div class="col-md-1 question-number-container">
                                        <span class="edit-exam-question-number m-t-10"> {{$examAnswer->question_number}}.</span>
                                    </div>
                                    <div class="col-md-11">
                                        <input type="text" class="form-control" name="topic_{{$examAnswer->question_number}}" placeholder="Topic ..." value="{{$examAnswer->topic}}">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </tab>
                <tab name="answer explanations">
                    <div class="col-md-11 card-box">
                        @foreach($examAnswers as $examAnswer)
                            <div class="row form-group">
                                <div class="col-md-1 question-number-container">
                                    <span class="edit-exam-question-number"> {{$examAnswer->question_number}}.</span>
                                </div>
                                <div class="col-md-11">
                                    <div class="col-md-12">
                                        <label class="control-label" for="answer_explanation_{{$examAnswer->question_number}}">Answer Explanation</label>
                                        <textarea class="form-control" name="answer_explanation_{{$examAnswer->question_number}}" placeholder="Answer Explanation" rows="8">{{ $examAnswer->explanation }}</textarea>
                                    </div>
                                    <div class="col-md-12 m-t-15">
                                        <upload-image :image="{{$examAnswer}}" :image-url="`{{$examAnswer->explanation_image}}`" />
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </tab>
            </tabs>
            <div class="row">
                <div class="form-group col-md-10 col-md-offset-1 text-right">
                    <button type="submit" class="btn btn-md btn-info">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
