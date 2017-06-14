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

    <div class="row create-container" id="create-question">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            @if($item->photo != '')
                            <div class="col-sm-6 col-sm-offset-3">
                                <img src="{{ $item->photo }}" class="img-responsive" alt="profile-image">
                            </div>
                            @endif
                            <div class="col-xs-12 text-center">
                                <h4 class="header-title">{{ $item->title }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12"><hr/></div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ $item->topic->subject->grade_level->name }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Subject:</label>
                        <p>{{ $item->topic->subject->name }}</p>
                    </div>
                    <div class="col-sm-4">
                        <label class="control-label">Topic:</label>
                        <p>{{ $item->topic->name }}</p>
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0 m-b-15">Answers</h4>
                    </div>
                </div>

                <ul class="list-group">
                    @foreach($item->answers as $answer)                    
                    <li class="list-group-item {{ $answer->is_correct? 'list-group-item-success' : '' }}">
                        @if($answer->text)
                        <h4 class="list-group-item-heading">{{ $answer->text }}</h4>
                        @endif
                        @if($answer->photo)
                        <img src="{{ $answer->photo }}" class="img-responsive">
                        @endif
                    </li>
                    @endforeach
                </ul>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('quizzes.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('quizzes.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
