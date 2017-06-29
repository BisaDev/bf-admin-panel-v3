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

    <div class="row create-container" id="create-quiz">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->title }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <label>Description</label>
                        <p>{{ $item->description }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Type</label>
                        <p>{{ $item->type->name }}</p>
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
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ $item->subject->grade_level->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ $item->subject->name }}</p>
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Questions</h4>
                        <ol>
                        @foreach($item->questions as $key => $question)
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
