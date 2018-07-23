@extends('layouts.master')

@section('page_title', 'Edit Exam')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit Exam ' . $exam->test_id . ' section: ' . $examQuestions->first()->section_number,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' =>  route('exams.index')],
            [ 'label' => $exam->test_id, 'url' =>  route('exams.show', $exam->id)],
        ],
        'currentSection' => 'Edit Exam'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive table-hover model-list text-center">
                            <thead>
                                <tr>
                                    <th>Question Number</th>
                                    <th>Correct 1</th>
                                    <th>Correct 2</th>
                                    <th>Correct 3</th>
                                    <th>Correct 4</th>
                                    <th>Correct 5</th>
                                    <th class="text-center">Topic</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($examQuestions as $question)
                                    <tr>
                                        <td> {{ $question->question_number }} </td>
                                        <td> {{ $question->correct_1 }} </td>
                                        <td> {{ $question->correct_2 }} </td>
                                        <td> {{ $question->correct_3 }} </td>
                                        <td> {{ $question->correct_4 }} </td>
                                        <td> {{ $question->correct_5 }} </td>
                                        <td> {{ $question->topic }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
