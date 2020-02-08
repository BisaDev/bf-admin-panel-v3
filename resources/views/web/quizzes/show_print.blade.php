@section('page_title', 'Meetup Student details')
@extends('layouts.print')

@section('content')
    <div class="container-alt">
        <div class="row create-container" id="create-quiz">
            <div class="col-xs-12">
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
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ ($item->subject)? $item->subject->grade_level->name : '' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ ($item->subject)? $item->subject->name : '' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Questions</h4>
                        <ol>
                            @foreach($item->questions as $key => $question)
                                <li>
                                    <strong>{{ $question->title ?? '' }}</strong>
                                    @if($question->photo)
                                        <img src="{{ $question->photo }}" class="img-responsive thumbnail m-t-5">
                                    @endif
                                    <div class="row answer-list m-t-10">
                                        @if($item->type->name != 'Fill the blank')
                                            @foreach($question->answers as $answer)
                                                <div class="col-lg-3 col-sm-6 text-center answer-item">
                                                    <div>
                                                        @if($answer->photo)
                                                            <img src="{{ $answer->photo }}"
                                                                 class="img-responsive thumbnail m-b-5">
                                                        @endif
                                                        {{ $answer->text ?? '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
