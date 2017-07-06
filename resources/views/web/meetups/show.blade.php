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
                        <h3 class="m-t-0">{{ ($item->room)? $item->room->location->name : '' }}</h3>
                        <h4 class="m-b-20">{{ ($item->room)? $item->room->name : '' }}</h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3 col-sm-9 col-sm-offset-0">
                                <label>Instructor</label>
                                <h4 class="m-b-20">{{ ($item->user)? $item->user->full_name : '' }}</h4>
                            </div>
                            <div class="col-xs-3">
                                @if(!is_null($item->user))
                                <img src="{{ $item->user->user_detail->photo }}" class="img-responsive img-circle">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-lg-3">
                        <label>Date</label>
                        <p>{{ $item->start_time->format('m/d/Y') }}</p>
                    </div>
                    <div class="col-xs-6 col-lg-3">
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
    
                @if(!is_null($item->activity_bucket))
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
                                <p>{{ ($item->activity_bucket->subject)? $item->activity_bucket->subject->grade_level->name : '' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="control-label">Subject:</label>
                                <p>{{ ($item->activity_bucket->subject)? $item->activity_bucket->subject->name : '' }}</p>
                            </div>
                        </div>
    
                        @include('partials.quizzes-list', ['quizzes' => $item->activity_bucket->quizzes])
                    </div>
                    <div class="tab-pane" id="student-details">
                        <div class="row">
                        @foreach($item->students as $student)
                            <div class="col-sm-3 col-xs-6 text-center">
                                <a href="{{ route('meetups.student_detail', [$item->id, $student->id]) }}"><img src="{{ $student->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true"></a>
                                <a href="{{ route('meetups.student_detail', [$item->id, $student->id]) }}"><h3>{{ $student->full_name }}</h3></a>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <div class="row m-t-20">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('meetups.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('meetups.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
