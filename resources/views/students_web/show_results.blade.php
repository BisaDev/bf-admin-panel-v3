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

    <div id="show-results" class="container">
        @foreach($item->sections as $section)
            <div class="row">
                <div class="card-box col-md-12">
                    <h3> Section {{$section->section_number}} : {{ $sectionData[$section->section_number]['name'] }}</h3>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="col-md-12">
                            <p>Hi {{$item->student->name}}, you got <strong>{{$section->number_correct}}</strong> correct out of
                                a possible <strong>{{$sectionData[$section->section_number]['questions']}}</strong> on {{$sectionData[$section->section_number]['name']}}.</p>
                            <p>You took {{$section->time}} minutes out of a possible {{$sectionData[$section->section_number]['timeAvailable']}} minutes.</p>
                        </div>
                    </div>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        @foreach($topics as $topic)
                            @if($topic['section'] === $section->section_number)
                                <div class="col-md-6 text-right">
                                    <span>{{$topic['topic']}}</span>
                                </div>

                                <div class="col-md-5">
                                    <div class="progress progress-lg">
                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: {{$topic['score']}}%">{{$topic['right']}}</div>
                                        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: {{100 - $topic['score']}}%" >{{$topic['wrong']}}</div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{ route('answer_sheet.edit_understood', $section->id)}}" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}

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
                                                <td><span class="badge badge-pill badge-success"><i class="ti-check"></i></span></td>
                                            @else
                                                <td><span class="badge badge-danger">Incorrect</span></td>
                                                @if($question->understood)
                                                    <td><span class="badge badge-pill badge-success"><i class="ti-check"></i></span></td>
                                                @else
                                                    <td><input type="checkbox" name={{'understood_' . $section->section_number . '_' . $question->question_number}}></td>
                                                @endif
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="row">
                                    <div class="form-group col-md-12 text-right">
                                        <button type="submit" class="btn btn-md btn-info">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection
