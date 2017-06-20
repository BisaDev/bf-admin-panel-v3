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

                <div class="row"><div class="col-sm-12"><hr/></div></div>

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
                                        @if($question->title)
                                        <strong>{{ $question->title }}</strong>
                                        @elseif($question->photo)
                                        <img src="{{ $question->photo }}" class="img-responsive thumbnail">
                                        @endif
                                        <ul class="list-group m-t-10">
                                            @foreach($question->answers as $answer)
                                            <li class="list-group-item {{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                                @if($answer->text)
                                                {{ $answer->text }}
                                                @elseif($answer->photo)
                                                <img src="{{ $answer->photo }}" class="img-responsive thumbnail">
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                                </ol>
                            </div>
                        </div>
                    </div>
                    @endforeach
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
