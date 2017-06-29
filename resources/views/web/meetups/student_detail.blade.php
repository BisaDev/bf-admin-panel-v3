@section('page_title', 'Meetup Student details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Meetup Student details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Meetups', 'url' => route('meetups.index')],
            [ 'label' => 'Meetup of '.$meetup->start_time->format('m/d/Y g:i a'), 'url' => route('meetups.show', $meetup->id)]
        ],
        'currentSection' => 'Meetup Student details',
    ])
@endsection

@section('content')
    
    <div class="row create-container" id="create-meetup" data-meetup-id="{{ $meetup->id }}" data-notes="{{ $student->meetup_student_pivot($meetup->id)->first()->notes->toJson() }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="m-t-0">{{ $meetup->room->location->name }}</h3>
                        <h4 class="m-b-20">{{ $meetup->room->name }}</h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="row">
                            <div class="col-xs-3 col-xs-offset-3 col-sm-offset-5">
                                <img src="{{ $meetup->user->user_detail->photo }}" class="img-responsive img-circle">
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <label>Instructor</label>
                                <h4 class="m-b-20">{{ $meetup->user->full_name }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Date</label>
                        <p>{{ $meetup->start_time->format('m/d/Y') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Time</label>
                        <p>{{ $meetup->start_time->format('g:i a') }} - {{ $meetup->end_time->format('g:i a') }}</p>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-md btn-info">Go to Student</a>
                        <a href="{{ route('meetups.show', $meetup->id) }}" class="btn btn-md btn-info">Go to Meetup</a>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-sm-12"><hr/></div>
                </div>
                
                <div class="row m-b-20">
                    <div class="col-xs-1">
                        <img src="{{ $student->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                    </div>
                    <div class="col-xs-11">
                        <h4>{{ $student->full_name }}</h4>
                    </div>
                </div>
    
                <form action="{{ route('meetups.student_detail', [$meetup->id, $student->id]) }}" method="POST">
                    {{ csrf_field() }}
                    
                    <div class="row">
                        @include('partials.add-notes')
                    </div>
    
                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-md btn-primary">Save notes</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>

@endsection
