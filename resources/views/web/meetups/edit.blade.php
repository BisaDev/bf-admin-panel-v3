@section('page_title', 'Edit meetup')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit meetup',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Meetups', 'url' => route('meetups.index')]
        ],
        'currentSection' => 'Edit meetup',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-meetup">
        <form action="{{ route('meetups.update', $item->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="col-md-10 col-md-offset-1">
                <div class="card-box">
                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('date')? 'has-error' : '' }}">
                            <label class="control-label" for="date">Date:</label>
                            <input type="text" name="date" class="form-control datepicker-meetup" value="{{ old('date', $item->start_time->format('m/d/Y')) }}">
                            @if($errors->has('date'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('start_time')? 'has-error' : '' }}">
                            <label class="control-label" for="start_time">Start Time:</label>
                            <input type="text" name="start_time" class="form-control timepicker" value="{{ old('start_time', $item->start_time->format('g:i a')) }}">
                            @if($errors->has('start_time'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('start_time') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 {{ $errors->has('end_time')? 'has-error' : '' }}">
                            <label class="control-label" for="end_time">End Time:</label>
                            <input type="text" name="end_time" class="form-control timepicker" value="{{ old('end_time', $item->end_time->format('g:i a')) }}">
                            @if($errors->has('end_time'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('end_time') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('location')? 'has-error' : '' }}">
                            <label class="control-label" for="location">Location:</label>
                            <select id="location" name="location" class="form-control" data-selected="{{ old('location', $item->room->location->id) }}" @change="getRoomsAndInstructorsFromLocation('{{ route('rooms.by_location') }}', '{{ route('employees.by_location') }}', $event)">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ ucfirst($location->name) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('room')? 'has-error' : '' }}">
                            <label class="control-label" for="room">Room:</label>
                            <select id="room" name="room" class="form-control" data-selected="{{ old('room', $item->room->id) }}">
                            </select>
                            @if($errors->has('room'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('room') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 {{ $errors->has('user')? 'has-error' : '' }}">
                            <label class="control-label" for="user">Instructor:</label>
                            <select id="user" name="user" class="form-control" data-selected="{{ old('user', $item->user->id) }}">
                            </select>
                            @if($errors->has('user'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="checkbox checkbox-primary">
                                <input id="existing_ab" name="existing_ab" type="checkbox" v-model="add_existing_activity_bucket" @change="loadActivityBuckets('{{ route('activity_buckets.for_meetup') }}', $event)" data-checked="{{ ($item->activity_bucket)? $item->activity_bucket->id : '' }}">
                                <label for="existing_ab">Add existing activity bucket</label>
                            </div>
                        </div>
                    </div>

                    <div v-if="add_existing_activity_bucket">
                        <div class="row"><div class="col-sm-12"><hr/></div></div>

                        <div class="row">
                            <div class="form-group col-sm-6 {{ $errors->has('grade_level')? 'has-error' : '' }}">
                                <label class="control-label" for="grade_level">Grade Level:</label>
                                <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ old('grade_level', $item->activity_bucket->subject->grade_level->id) }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
                                    <option value="">Select Grade Level</option>
                                    @foreach($grade_levels as $grade_level)
                                    <option value="{{ $grade_level->id }}">{{ ucfirst($grade_level->name) }}</option>
                                    @endforeach
                                </select>
                                @if($errors->has('grade_level'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('grade_level') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group col-sm-6 {{ $errors->has('subject')? 'has-error' : '' }}">
                                <label class="control-label" for="subject">Subject:</label>
                                <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', $item->activity_bucket->subject->id) }}" v-model="subject" @change="loadActivityBuckets('{{ route('activity_buckets.for_meetup') }}', $event)">
                                </select>
                                @if($errors->has('subject'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('subject') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <h4 class="header-title">Add Existing Activity Bucket</h4>
                                <p class="text-muted">Select Subject to load available Activity Buckets.</p>
                                @if($errors->has('activity_bucket'))
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('activity_bucket') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <table class="table table-responsive table-hover">
                            <thead>
                            <tr>
                                <th width="50">Add</th>
                                <th>Title</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activity_bucket in activity_buckets">
                                    <td>
                                        <div class="radio radio-primary">
                                            <input type="radio" name="activity_bucket" v-bind:value="activity_bucket.id" :checked="activityBucketSelected(activity_bucket)">
                                            <label></label>
                                        </div>
                                    </td>
                                    <td>@{{ activity_bucket.title }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('meetups.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary" v-if="add_existing_activity_bucket">Edit</button>
                            <button type="submit" class="btn btn-md btn-primary" v-else>Save and Next Step</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
