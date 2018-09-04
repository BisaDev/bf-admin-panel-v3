@section('page_title', 'Meetups')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Meetups',
        'link' => [ 'label' => 'Create Meetup', 'url' => route('meetups.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Meetups', 'url' => route('meetups.index')],
        ],
        'currentSection' => 'Weekly Meetups',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="meetup" class="row">
        <div class="col-xs-12">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
        </div>
        <div id="calendar" class="col-xs-12">
            <div class="row calendar-nav">
                <div class="col-md-2 text-left">
                    <a href="{{ route('meetups.index').'?start_of_week='.$start_of_week->copy()->subWeek()->format('Y-m-d') }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="btn btn-sm btn-info navbar-btn btn-block">&#171; Previous week</a>
                </div>
                <div class="col-md-offset-2 col-md-4 text-center">
                    <span class="nav-title">{{ $start_of_week->toFormattedDateString() }} - {{ $end_of_week->toFormattedDateString() }}</span>    
                </div>
                <div class="col-md-offset-2 col-md-2 text-right">
                    <a href="{{ route('meetups.index').'?start_of_week='.$start_of_week->copy()->addWeek()->format('Y-m-d') }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="btn btn-sm btn-info navbar-btn btn-block">Next week &#187;</a>
                </div>
            </div>
            <div class="row calendar-nav">
                <div class="col-xs-12">
                    <form class="form-inline" action="{{ route('meetups.filter') }}{{ (Request::getQueryString())? '?'.Request::getQueryString() : '' }}" method="POST">
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <select id="location" name="location" class="form-control">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (array_key_exists('location', $filters) && $filters['location'] == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" name="go_to_week" class="form-control datepicker-general" placeholder="Go to week">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                @foreach($meetups_by_date as $date)
                <div class="col-md-2 col-md-custom">
                    <div class="clearfix weekday-title">
                        <small>{{ $date['date']->format('m/d/Y') }}</small>
                        <h3 class="m-b-15">{{ $date['date']->format('l') }}</h3>
                    </div>
                    @if(array_key_exists('meetups', $date))
                    <div class="row">
                        @foreach($date['meetups'] as $meetup)
                        <div class="col-xs-12 col-sm-4 col-md-12">
                            <div class="panel panel-border panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><a href="{{ route('meetups.show', $meetup->id) }}">{{ ($meetup->room)? $meetup->room->location->name : '' }}</a></h3>
                                    <p class="panel-subtitle">{{ ($meetup->room)? $meetup->room->name : '' }}</p>
                                    <span class="status"><small>{{ $meetup->status->name }}</small></span>
                                </div>
                                <div class="panel-body">
                                    @if(!is_null($meetup->activity_bucket)) 
                                    <p class="meetup-title">{{ $meetup->activity_bucket->title }}</p>
                                    <p>{{ ($meetup->activity_bucket)? $meetup->activity_bucket->subject->grade_level->name : ''}}, {{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->name : '' }}</p>
                                    @endif
                                    <p class="m-t-0">{{ ($meetup->user)? $meetup->user->full_name : '' }}</p>
                                    <p class="m-t-0"><small>{{ $meetup->start_time->format('g:i a') }} - {{ $meetup->end_time->format('g:i a') }}</small></p>
                                    <a href="{{ route('meetups.attendance', $meetup->id) }}" class="icon icon-user pull-right m-l-5"></a>
                                    <a href="{{ route('meetups.edit', $meetup->id) }}" class="icon icon-pencil pull-right"></a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection