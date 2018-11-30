@extends('layouts.student_master')

@section('page_title', 'Results Page')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Results Page',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Results Page',
    ])
@endsection

@section('content')

    <div id="app" class="container">
        @foreach($studentExam->sections as $section)
            <div class="row">
                <div class="card-box col-md-12">
                    <div class="container">
                        <h3> Section {{$section->section_number}}
                            : {{ $studentExam->exam->IsMiniExam ? $studentExam->exam->test_id : $section->metadata->section_name }}</h3>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>

                    <div class="row container">
                        <div class="col-md-12">
                            @if($studentExam->exam->IsMiniExam)
                                <p>Hi {{ $studentExam->student->name }}, you got <strong>{{ $section->number_correct }}</strong>
                                    correct out of a possible <strong>{{ $studentExam->exam->mini_exam_questions }}</strong>
                                <p>You took {{ $section->time }} minutes out of a
                                    possible {{ $studentExam->exam->mini_exam_time }} minutes.</p>
                            @else
                                <p>Hi {{ $studentExam->student->name }}, you got <strong>{{ $section->number_correct }}</strong>
                                    correct out of
                                    a possible <strong>{{ $section->metadata->questions }}</strong>
                                    on {{ $section->metadata->section_name }}. That Calculates to a score of
                                    <strong>{{ $section->score }}</strong> out of a possible
                                    <strong>{{ $section->metadata->max_score }}</strong>.</p>
                                <p>You took {{ $section->time }} minutes out of a
                                    possible {{ $section->metadata->time_available }} minutes.</p>
                                @if(!$section->score)
                                    <p>Note: You have to complete both Math sections in order to get a Math Score.</p>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>

                    <div class="row">
                        @foreach($topics[$section->id] as $topic)
                            <div class="col-md-6 text-right">
                                <span>{{$topic['topic']}}</span>
                            </div>

                            <div class="col-md-5">
                                <div class="progress progress-lg">
                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                         style="width: {{$topic['score']}}%">{{$topic['right']}}</div>
                                    <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                                         style="width: {{100 - $topic['score']}}%">{{$topic['wrong']}}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="text-center m-b-15">
                            <span class="badge right badge-legend">Right</span>
                            <span class="badge wrong badge-legend">Wrong</span>
                            <span class="badge guessed-wrong badge-legend">Guessed-Wrong</span>
                            <span class="badge wrong-understood badge-legend">Wrong-Understood</span>
                            <span class="badge guessed-right badge-legend">Guessed-Right</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-hover model-list text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Correct Answer</th>
                                    <th class="text-center">Your Answer</th>
                                    <th class="text-center">I get it now</th>
                                    <th class="text-center">Topic</th>
                                    <th>&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($section->questions as $question)
                                    <tr is="show-results-table" :question="{{$question}}"
                                        :section="{{$section->section_number}}" :section-id="{{$section->id}}"
                                        :url=`{{route('answer_sheet.edit_understood', $section->id)}}`
                                        :correct-answer="{{$question->correctAnswer}}"
                                        :answer-background=`{{$question->BackgroundForReport}}`></tr>

                                    <div class="modal fade" id="answerExplanationModal_{{$question->id}}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Question {{$question->question_number}} Explanation</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($question->correctAnswer->explanation)
                                                        <p>{{$question->correctAnswer->explanation}}</p>
                                                    @else
                                                        <p>There is no explanation for this answer.</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="row">
            <div class="form-group col-md-12 text-center">
                <a href="{{ route('student_dashboard') }}" class="btn btn-lg btn-primary">Finish!</a>
            </div>
        </div>
    </div>
@endsection
