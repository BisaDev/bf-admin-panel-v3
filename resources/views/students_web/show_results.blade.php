@extends('layouts.student_master')

@section('page_title', 'Results Page')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Results Page',
        'breadcrumbs' => [],
        'currentSection' => '',
    ])
@endsection

@section('content')

    <div id="show-results" class="container">
        @foreach($item->sections as $section)
            <div class="row">
                <div class="card-box col-md-12">
                    <h3 class="m-b-0 m-t-0"> Section {{$section->section_number}} : {{ $sectionData[$section->section_number]['name'] }}</h3>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="col-md-12">
                            <p>Hi {{$item->student->name}}, you got <strong>{{$section->number_correct}}</strong> correct out of
                                a possible <strong>{{$sectionData[$section->section_number]['questions']}}</strong> on {{$sectionData[$section->section_number]['name']}}.</p>

                        </div>
                    </div>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="col-md-6 text-right">
                            <span>Here goes the topic</span>
                        </div>

                        <div class="col-md-5">
                            <div class="progress">
                                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%"></div>
                                <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 75%" ></div>
                            </div>
                        </div>
                    </div>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive table-hover model-list text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Question</th>
                                    <th class="text-center">Your Answer</th>
                                    <th class="text-center">Correct Answer</th>
                                    <th class="text-center">Correct / Incorrect</th>
                                    <th class="text-center">I get it now</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($section->questions as $question)
                                    <tr>
                                        <td>{{ $question->question_number }}</td>
                                        <td>{{ $question->answer }}</td>
                                        <td>{{ $question->correctAnswer->correct_1 }}</td>
                                        @if($question->answer === $question->correctAnswer->correct_1 || $question->answer === $question->correctAnswer->correct_2 || $question->answer === $question->correctAnswer->correct_3 || $question->answer === $question->correctAnswer->correct_4 || $question->answer === $question->correctAnswer->correct_5)
                                            <td><span class="badge badge-success">Correct</span></td>
                                            <td></td>
                                        @else
                                            <td><span class="badge badge-danger">Incorrect</span></td>
                                            <td><input type="checkbox" name={{'understand_' . $question->question_number}}></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
