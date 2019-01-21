@extends('layouts.master')

@section('page_title', 'Exam Section')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Exam ' . $exam->test_id . ', Section: ' . $examQuestions->first()->section_number,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' =>  route('exams.index')],
            [ 'label' => $exam->test_id, 'url' =>  route('exams.show', $exam->id)],
        ],
        'currentSection' => 'Exam Section'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive table-bordered table-hover model-list text-center">
                            <thead>
                                <tr>
                                    <th class="text-center">Question Number</th>
                                    <th class="text-center">Correct 1</th>
                                    <th class="text-center">Correct 2</th>
                                    <th class="text-center">Correct 3</th>
                                    <th class="text-center">Correct 4</th>
                                    <th class="text-center">Correct 5</th>
                                    <th class="text-center">Correct 6</th>
                                    <th class="text-center">Correct 7</th>
                                    <th class="text-center">Correct 8</th>
                                    <th class="text-center">Correct 9</th>
                                    <th class="text-center">Topic</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($examQuestions as $question)
                                    <tr>
                                        <td> {{ $question->question_number }} </td>
                                        <td> {{ strtoupper($question->correct_1) }} </td>
                                        <td> {{ $question->correct_2 }} </td>
                                        <td> {{ $question->correct_3 }} </td>
                                        <td> {{ $question->correct_4 }} </td>
                                        <td> {{ $question->correct_5 }} </td>
                                        <td> {{ $question->correct_6 }} </td>
                                        <td> {{ $question->correct_7 }} </td>
                                        <td> {{ $question->correct_8 }} </td>
                                        <td> {{ $question->correct_9 }} </td>
                                        <td> {{ $question->topic }} </td>
                                        <td data-toggle="tooltip" title="Answer Explanation"><a href="#" data-toggle="modal" data-target="#answerExplanationModal_{{$question->id}}"><i class="ti-info-alt"></i></a></td>
                                    </tr>
                                    <div class="modal fade" id="answerExplanationModal_{{$question->id}}" tabindex="-1"
                                         role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Question {{$question->question_number}}
                                                        Explanation</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if($question->explanation)
                                                        <p>{{$question->explanation}}</p>
                                                    @else
                                                        <p>There is no explanation for this answer.</p>
                                                    @endif
                                                    @if($question->explanation_image)
                                                        <img src="{{ $question->explanation_image }}"
                                                             class="modal-image center-block">
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">
                                                        Close
                                                    </button>
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
    </div>
@endsection
