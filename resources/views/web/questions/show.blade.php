@section('page_title', 'Question details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Question details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('questions.index')]
        ],
        'currentSection' => 'Question details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-question">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            @if($item->other_photo && $item->photo)
                                <div class="row">
                                    <div class="col-md-5 col-md-offset-1">
                                        <p class="text-center m-t-10">{{$item->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                        <img src="{{ $item->other_photo }}" class="img-responsive thumbnail m-t-5">
                                    </div>
                                    <div class="col-md-5">
                                        <p class="text-center m-t-10">Question Image</p>
                                        <img src="{{ $item->photo }}" class="img-responsive thumbnail m-t-5">
                                    </div>
                                </div>
                            @else
                                @if($item->other_photo)
                                    <div class="col-md-5 col-md-offset-3">
                                        <p class="text-center m-t-10">{{$item->type->key == 7 ? 'Long Passage Image' : 'Equation Image'}}</p>
                                        <img src="{{ $item->other_photo }}" class="img-responsive thumbnail m-t-5">
                                    </div>
                                @endif
                                @if($item->photo)
                                    <div class="col-md-5 col-md-offset-3">
                                        <p class="text-center m-t-10">Question Image</p>
                                        <img src="{{ $item->photo }}" class="img-responsive thumbnail m-t-5">
                                    </div>
                                @endif
                            @endif
                            <div class="col-xs-12">
                                <h3 class="m-b-0 m-t-10">{{ $item->title }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr/>
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
                    <div class="col-sm-4">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ ($item->topic)? $item->topic->subject->grade_level->name : '' }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Subject:</label>
                        <p>{{ ($item->topic)? $item->topic->subject->name : '' }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Topic:</label>
                        <p>{{ ($item->topic)? $item->topic->name : '' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <label class="control-label">Created by:</label>
                        <p>{{ ($item->user)? $item->user->full_name : '' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0 m-b-15">Answers</h4>
                    </div>
                </div>

                <div class="row answer-list m-t-10">
                    @foreach($item->answers as $answer)
                        <div class="col-lg-3 col-sm-6 text-center answer-item">
                            <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                @if($answer->photo)
                                    <img src="{{ $answer->photo }}" class="img-responsive thumbnail m-b-5">
                                @endif
                                {{ $answer->text or '' }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('questions.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('questions.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
