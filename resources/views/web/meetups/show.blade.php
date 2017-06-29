@section('page_title', 'Meetup details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Meetup details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Meetups', 'url' => route('meetups.index')]
        ],
        'currentSection' => 'Meetup details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-meetup" data-meetup-id="{{ $item->id }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="m-t-0">{{ $item->room->location->name }}</h3>
                        <h4 class="m-b-20">{{ $item->room->name }}</h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <label>Instructor</label>
                        <h4 class="m-b-20">{{ $item->user->full_name }}</h4>
                        <div class="row">
                            <div class="col-xs-6 col-sm-3 pull-right">
                                <img src="{{ $item->user->user_detail->photo }}" class="img-responsive img-circle">  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Date</label>
                        <p>{{ $item->start_time->format('m/d/Y') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Time</label>
                        <p>{{ $item->start_time->format('g:i a') }} - {{ $item->end_time->format('g:i a') }}</p>
                    </div>
                </div>

                <ul class="nav nav-tabs tabs m-t-30">
                    <li class="active tab">
                        <a href="#activities" data-toggle="tab" aria-expanded="false"> 
                            <span class="visible-xs"><i class="ti-pencil-alt"></i></span> 
                            <span class="hidden-xs">Activities</span> 
                        </a> 
                    </li> 
                    <li class="tab"> 
                        <a href="#student-details" data-toggle="tab" aria-expanded="false"> 
                            <span class="visible-xs"><i class="ti-user"></i></span> 
                            <span class="hidden-xs">Students Details</span> 
                        </a> 
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="activities"> 
                        <div class="row">
                            <div class="col-sm-12">
                                <h3>{{ $item->activity_bucket->title }}</h3>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <label class="control-label">Grade Level:</label>
                                <p>{{ $item->activity_bucket->subject->grade_level->name }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Subject:</label>
                                <p>{{ $item->activity_bucket->subject->name }}</p>
                            </div>
                        </div>

                        <div class="list-group">
                            @foreach($item->activity_bucket->quizzes as $quiz)
                            <div class="list-group-item">
                                <h4 class="list-group-item-heading">{{ $quiz->title }}</h4>
                                <p class="list-group-item-text">{{ $quiz->description }}</p>
                                <div class="row m-t-15">
                                    <div class="col-xs-12">
                                        <ol>
                                        @foreach($quiz->questions as $key => $question)
                                            <li>
                                                <strong>{{ $question->title or '' }}</strong>
                                                @if($question->photo)
                                                <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                                @endif
                                                <div class="row answer-list m-t-10">
                                                    @foreach($question->answers as $answer)
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
                                            </li>
                                        @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane" id="student-details">
                        <div class="row">
                        @foreach($item->students as $student)
                            <div class="col-sm-3 col-xs-6 text-center">
                                <img src="{{ $student->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                                <h3>{{ $student->full_name }}</h3>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('meetups.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('meetups.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
