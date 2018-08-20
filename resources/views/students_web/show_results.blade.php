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
        @foreach($item->sections as $section)
            <div class="row">
                <div class="card-box col-md-12">
                    <div class="container">
                        <h3> Section {{$section->section_number}}
                            : {{ $sectionData[$section->section_number]['name'] }}</h3>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>

                    <div class="row container">
                        <div class="col-md-12">
                            <p>Hi {{$item->student->name}}, you got <strong>{{$section->number_correct}}</strong>
                                correct out of
                                a possible <strong>{{$sectionData[$section->section_number]['questions']}}</strong>
                                on {{$sectionData[$section->section_number]['name']}}. That Calculates to a score of
                                <strong>{{$section->score}}</strong>.</p>
                            <p>You took {{$section->time}} minutes out of a
                                possible {{$sectionData[$section->section_number]['timeAvailable']}} minutes.</p>
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
                        <div class="col-md-12">
                            <table class="table table-responsive table-hover model-list text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Your Answer</th>
                                    <th class="text-center">Correct Answer</th>
                                    <th class="text-center">Guessed Answer</th>
                                    <th class="text-center">Correct / Incorrect</th>
                                    <th class="text-center">I get it now</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($section->questions as $question)
                                    <tr is="show-results-table" :question="{{$question}}"
                                        :section="{{$section->section_number}}" :section-id="{{$section->id}}"
                                        :url=`{{route('answer_sheet.edit_understood', $section->id)}}`
                                        :correct-answer="{{$question->correctAnswer}}"></tr>
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
