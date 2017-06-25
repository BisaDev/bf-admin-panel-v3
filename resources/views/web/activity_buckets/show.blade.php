@section('page_title', 'Activity bucket details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Activity bucket details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Activity Buckets', 'url' => route('activity_buckets.index')]
        ],
        'currentSection' => 'Activity bucket details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-activity-bucket">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->title }}</h3>
                    </div>
                </div>

                <div class="row m-b-20">
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ $item->subject->grade_level->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ $item->subject->name }}</p>
                    </div>
                </div>

                <div class="list-group">
                    @foreach($item->quizzes as $quiz)
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
                        <a href="{{ route('activity_buckets.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('activity_buckets.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
