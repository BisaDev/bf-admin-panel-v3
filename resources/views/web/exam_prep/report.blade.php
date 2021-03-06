@extends('layouts.master')

@section('page_title', 'Detailed Report')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Detailed Report',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Logs', 'url' =>  route('exams.logs')]
        ],
        'currentSection' => 'Detailed Report',
    ])
@endsection

@section('content')
    <div class="row container">
        <div class="col-md-12 card-box">
            <div class="container">
                <h3> {{ $exam->IsMiniExam ? $exam->test_id : $exam->test_id . ', Section ' . $studentExamSections->first()->section_number . ': ' . $studentExamSections->first()->metadata->section_name}}</h3>
            </div>

            <div class="row"><div class="col-sm-12"><hr/></div></div>

            <div class="row container">
                @foreach($answersByTopic as $answerByTopic)
                    <div class="col-md-6 text-right">
                        <span>{{$answerByTopic['topic']}}</span>
                    </div>

                    <div class="col-md-5">
                        <div class="progress progress-lg">
                            <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                 style="width: {{$answerByTopic['score']}}%">{{$answerByTopic['right']}}</div>
                            <div class="progress-bar progress-bar-striped bg-danger" role="progressbar"
                                 style="width: {{100 - $answerByTopic['score']}}%">{{$answerByTopic['wrong']}}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="row"><div class="col-sm-12"><hr/></div></div>

            <div class="row">
                <div class="text-center m-b-15">
                    <span class="badge a-wrong badge-legend">Wrong</span>
                    <span class="badge b-guessed-wrong badge-legend">Guessed-Wrong</span>
                    <span class="badge c-wrong-understood badge-legend">Wrong-I get it now</span>
                    <span class="badge d-guessed-right badge-legend">Guessed-Right</span>
                    <span class="badge e-right badge-legend">Right</span>
                </div>
            </div>

            <table class="table table-responsive table-bordered table-hover model-list text-center">
                <thead>
                    <tr>
                        <th class="text-center">Question <a href="{{ route('exams.generate_report', $requestData).'&sort_column=question_number&sort_value='.$sort_columns['question_number'] }}" class="fa fa-sort"></a></th>
                        <th class="text-center">Correct Answer</th>
                        <th class="text-center">% Correct <a href="{{ route('exams.generate_report', $requestData).'&sort_column=score&sort_value='.$sort_columns['score'] }}" class="fa fa-sort"></a></th>
                        @foreach($studentExamSections as $studentExamSection)
                            <th class="text-center">{{$studentExamSection->studentExam->student->name}}</th>
                        @endforeach
                        <th class="text-center">Topic <a href="{{ route('exams.generate_report', $requestData).'&sort_column=topic&sort_value='.$sort_columns['topic'] }}" class="fa fa-sort"></a></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($answers as $answer)
                    <tr>
                        <td> {{ $answer->question_number }}</td>
                        <td> {{ strtoupper($answer->correct_1) }}</td>
                        <td> {{ $answersByQuestion[$answer->question_number]['score'] }}% </td>
                        @foreach($studentExamSections as $studentExamSection)
                            <td class="{{$studentExamSection->questions->where('question_number', $answer->question_number)->first()->BackgroundForReport}}">{{ strtoupper($studentExamSection->questions->where('question_number', $answer->question_number)->first()->answer) }}</td>
                        @endforeach
                        <td> {{ $answer->topic }} </td>
                        <td data-toggle="tooltip" title="Answer Explanation"><a href="#" data-toggle="modal" data-target="#answerExplanationModal_{{$answer->id}}"><i class="ti-info-alt"></i></a></td>
                    </tr>

                    <div class="modal fade" id="answerExplanationModal_{{$answer->id}}" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Question {{$answer->question_number}} Explanation</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    @if($answer->explanation)
                                        <p>{{$answer->explanation}}</p>
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
@endsection
