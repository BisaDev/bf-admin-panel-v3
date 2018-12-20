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
        <div class="row">
            <div class="col-md-10 col-md-offset-2">
                <form action="{{ route('meetups.filter') }}{{ (Request::getQueryString())? '?'.Request::getQueryString() : '' }}" method="POST">
                    {{ csrf_field() }}
                    <div class="col-md-offset-5 col-md-3">
                        <select id="location" name="location" class="form-control">
                            <option value="">Select Location</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ (array_key_exists('location', $filters) && $filters['location'] == $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="go_to_week" class="form-control datepicker-general" placeholder="Go to week">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <button type="submit" class="btn btn-info">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="calendar" class="col-xs-12">
            <div class="row calendar-nav">
                <div class="col-md-4 text-center">
                    <span class="nav-title">{{ $start_of_week->toFormattedDateString() }} - {{ $end_of_week->toFormattedDateString() }}</span>
                </div>
                <div class="col-md-2 text-left col-md-offset-4">
                    <a href="{{ route('meetups.index').'?start_of_week='.$start_of_week->copy()->subWeek()->format('Y-m-d') }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="btn btn-sm btn-info navbar-btn btn-block">&#171; Previous week</a>
                </div>
                <div class="col-md-2 text-right">
                    <a href="{{ route('meetups.index').'?start_of_week='.$start_of_week->copy()->addWeek()->format('Y-m-d') }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="btn btn-sm btn-info navbar-btn btn-block">Next week &#187;</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @foreach($meetups_by_date as $date)
                        @if((array_key_exists('meetups', $date)))
                            <div class="row">
                            <div class="col-md-2">
                                <div class="clearfix weekday-title">
                                    <h3>{{ $date['date']->format('l') }}</h3>
                                    <small>{{ $date['date']->format('m/d/Y') }}</small>
                                </div>
                            </div>
                            @if(array_key_exists('meetups', $date))
                                <div class="col-md-10 horizontal-scroll-cards">
                                    @foreach($date['meetups'] as $meetup)
                                        <div class="col-md-3 meetup-card">
                                            <div class="panel panel-border panel-left-border">
                                                <div class="panel-heading panel-custom-heading">
                                                    <p class="m-t-0">{{ ($meetup->user)? $meetup->user->full_name : '' }}</p>
                                                    <p class="m-t-0">{{ $date['date']->format('m/d/Y') }}</p>
                                                </div>
                                                <div class="panel-body">
                                                    @if(!is_null($meetup->activity_bucket))
                                                        <div class="panel-block">
                                                            <p class="meetup-title"><a href="{{ route('meetups.show', $meetup->id) }}">{{ $meetup->activity_bucket->title }}</a></p>
                                                            <p class="m-t-0">{{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->name : '' }} - {{ ($meetup->activity_bucket)? $meetup->activity_bucket->subject->grade_level->name : ''}}</p>
                                                        </div>
                                                    @endif
                                                    <div class="panel-block">
                                                        <p class="meetup-title">{{ ($meetup->room)? $meetup->room->location->name : '' }}</p>
                                                        <p class="m-t-0">{{ ($meetup->room)? $meetup->room->name : '' }}</p>
                                                        <span class="status"><small>{{ $meetup->status->name }}</small></span>
                                                    </div>
                                                    <small>{{ $meetup->start_time->format('g:i a') }} - {{ $meetup->end_time->format('g:i a') }}</small>
                                                    <a href="{{ route('meetups.attendance', $meetup->id) }}" class="icon icon-user pull-right m-l-15"></a>
                                                    <a href="" @click="confirmDelete({{ $meetup->id }}, 0, $event)" class="icon icon-trash pull-right m-l-15"></a>
                                                    <form id="delete-form-{{ $meetup->id }}" action="{{ route('meetups.destroy', $meetup->id) }}" method="POST" style="display: none;">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}
                                                    </form>
                                                    <a href="{{ route('meetups.edit', $meetup->id) }}" class="icon icon-pencil pull-right"></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection