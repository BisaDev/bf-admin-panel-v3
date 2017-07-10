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
                        <h3 class="m-t-0">{{ ($meetup->room)? $meetup->room->location->name : ''}}</h3>
                        <h4 class="m-b-20">{{ ($meetup->room)? $meetup->room->name : ''}}</h4>
                    </div>
                    <div class="col-sm-6 text-right">
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-3 col-sm-9 col-sm-offset-0">
                                <label>Instructor</label>
                                <h4 class="m-b-20">{{ ($meetup->user)? $meetup->user->full_name : '' }}</h4>
                            </div>
                            <div class="col-xs-3">
                                @if($meetup->user)
                                <img src="{{ $meetup->user->user_detail->photo }}" class="img-responsive img-circle">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-lg-3">
                        <label>Date</label>
                        <p>{{ $meetup->start_time->format('m/d/Y') }}</p>
                    </div>
                    <div class="col-xs-6 col-lg-3">
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
                
                @if($meetup->graded_quizzes->count() > 0)
                    <div class="row results-title">
                        <div class="col-xs-12">
                            <h3>{{ $meetup->activity_bucket->title }} - Results</h3>
                        </div>
                    
                        <div class="col-sm-5">
                            <label class="control-label">Grade Level:</label>
                            <p>{{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->grade_level->name : '' }}</p>
                        </div>
                        <div class="col-sm-5">
                            <label class="control-label">Subject:</label>
                            <p>{{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->name : '' }}</p>
                        </div>
                        <div class="col-sm-2 text-right">
                            <a href="{{ route('meetups.student_detail_print', [$meetup->id, $student->id]) }}" class="icon icon-printer"></a>
                        </div>
                    </div>
                @endif
                
                <div class="list-group">
                    @foreach($meetup->graded_quizzes as $key => $graded_quiz)
                        <div class="list-group-item">
                            <h4 class="list-group-item-heading">
                                <a role="button" data-toggle="collapse" href="#collapse{{ $key }}">
                                    {{ $graded_quiz->quiz_title }}
                                </a>
                            </h4>
                            <div id="collapse{{ $key }}" class="panel-collapse collapse">
                                <p class="list-group-item-text">{{ $graded_quiz->quiz_description }}</p>
                                <div class="row m-t-15">
                                    <div class="col-xs-12">
                                        <ol>
                                            @foreach($graded_quiz->questions as $key => $question)
                                                <li>
                                                    <strong>{{ $question->question_title or '' }}</strong>
                                                    @if($question->question_photo)
                                                        <img src="{{ $question->question_photo }}" class="img-responsive thumbnail m-t-5">
                                                    @endif
                                                    @if(strpos($graded_quiz->quiz_type->name, 'Trivia') === false)
                                                    <div class="row answer-list m-t-10">
                                                        @foreach($question->answers as $answer)
                                                            <div class="col-lg-3 col-sm-6 text-center answer-item">
                                                                <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                                                    @if($answer->original_photo)
                                                                        <img src="{{ $answer->original_photo }}" class="img-responsive thumbnail m-b-5">
                                                                    @endif
                                                                    {{ $answer->text or '' }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                    @if($student->graded_answer($question->id)->first())
                                                    <div class="row answer-list m-t-5">
                                                        <div class="col-sm-6 col-sm-offset-3 text-center answer-item">
                                                            <h3>{{ $student->name  }} answered:</h3>
                                                            <div class="{{ ($student->graded_answer($question->id)->first()->is_correct)? 'list-group-item-success' : '' }}">
                                                                @if($student->graded_answer($question->id)->first()->answer_image)
                                                                    <img src="{{ $student->graded_answer($question->id)->first()->answer_image }}" class="img-responsive thumbnail m-b-5">
                                                                @endif
                                                                {{ $student->graded_answer($question->id)->first()->answer_text or '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
            </div>
        </div>
    </div>

@endsection
