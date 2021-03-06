@section('page_title', 'Quiz details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Quiz details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Quizzes', 'url' => route('quizzes.index')]
        ],
        'currentSection' => 'Quiz details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->title }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <label>Description</label>
                        <p>{{ $item->description }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Type</label>
                        <p>{{ $item->type->name }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 m-b-15">
                        @foreach($item->tags as $tag)
                        <span class="label label-primary">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ ($item->subject)? $item->subject->grade_level->name : '' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ ($item->subject)? $item->subject->name : '' }}</p>
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Questions</h4>
                        <ol>
                        @foreach($item->questions as $key => $question)
                            <li>
                                <strong>{{ $question->title ?? '' }}</strong>
                                    @if($question->other_photo && $question->photo)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="text-center m-t-10">{{$question->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                                <img src="{{ $question->other_photo }}" class="img-responsive thumbnail m-t-5">
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-center m-t-10">Question Image</p>
                                                <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                            </div>
                                        </div>
                                    @else
                                        @if($question->other_photo)
                                            <p class="text-center m-t-10">{{$question->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                            <img src="{{ $question->other_photo }}" class="img-responsive thumbnail m-t-5">
                                        @endif
                                        @if($question->photo)
                                            <p class="text-center m-t-10">Question Image</p>
                                            <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                        @endif
                                    @endif
                                <div class="row">
                                    <div class="col-sm-6 m-t-15">
                                        <label class="control-label">Topic:</label>
                                        <span> {{ $question->topic->name }}</span>
                                    </div>
                                    <div class="col-sm-6 m-t-15">
                                        <label class="control-label">Tags:</label>
                                        @foreach($question->tags as $tag)
                                            <span class="label label-primary">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @if($question->answer_explanation || $question->answer_explanation_photo)
                                    <div class="row">
                                        <div class="col-sm-6 m-t-15">
                                            <label class="control-label">Answer Explanation: </label>
                                            <span><a href="#" data-toggle="modal" data-target="{{'#answerExplanationModal_' . $key}}"><i class="ti-info-alt m-l-5"></i></a></span>
                                        </div>
                                    </div>
                                @endif
                                <div class="row answer-list m-t-10">
                                    @foreach($question->answers as $answer)
                                    <div class="col-lg-3 col-sm-6 text-center answer-item">
                                        <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                            @if($answer->photo)
                                            <img src="{{ $answer->photo }}" class="img-responsive thumbnail m-b-5">
                                            @endif
                                            {{ $answer->text ?? '' }}
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </li>
                                <div class="modal fade" id="{{ 'answerExplanationModal_' . $key }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Answer Explanation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if($question->answer_explanation)
                                                    <p>{{$question->answer_explanation}}</p>
                                                @endif
                                                @if($question->answer_explanation_photo)
                                                    <img src="{{ $question->answer_explanation_photo }}" class="modal-image center-block">
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('quizzes.show_print', $item->id) }}" class="btn btn-md btn-info" target="_blank">Print blank Quiz</a>
                        <a href="{{ route('quizzes.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('quizzes.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
