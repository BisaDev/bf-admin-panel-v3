@section('page_title', 'Meetup Student details')
@extends('layouts.print')

@section('content')
    
    <div class="row create-container" id="create-meetup">
        <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <h3 class="m-t-0">{{ ($meetup->room)? $meetup->room->location->name : ''}}</h3>
                        <h4 class="m-b-20">{{ ($meetup->room)? $meetup->room->name : ''}}</h4>
                    </div>
                    <div class="col-xs-6 text-right">
                        <div class="row">
                            <div class="col-xs-6 col-xs-offset-4">
                                <label>Instructor</label>
                                <h4 class="m-b-20">{{ ($meetup->user)? $meetup->user->full_name : '' }}</h4>
                            </div>
                            <div class="col-xs-2">
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
                    <div class="col-xs-12"><hr/></div>
                </div>
                
                <div class="row m-b-20">
                    <div class="col-xs-1">
                        <img src="{{ $student->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                    </div>
                    <div class="col-xs-11">
                        <h4>{{ $student->full_name }}</h4>
                    </div>
                </div>
    
                @if($student->meetup_student_pivot($meetup->id)->first()->notes->count() > 0)
                <div class="row">
                    <div class="col-xs-12">
                        <h3>Meetup Notes</h3>
                    </div>
                    <div class="col-xs-12">
                        <table class="table table-responsive table-hover model-list">
                            <tbody>
                            @foreach($student->meetup_student_pivot($meetup->id)->first()->notes as $note)
                                <tr>
                                    <td>{{ $note->title }}</td>
                                    <td>{{ $note->text }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                
                @if($meetup->graded_quizzes->count() > 0)
                <div class="row results-title">
                    <div class="col-xs-12">
                        <h3>{{ $meetup->activity_bucket->title }} - Results</h3>
                    </div>
            
                    <div class="col-xs-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->grade_level->name : '' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->name : '' }}</p>
                    </div>
                </div>
                @endif
                
                <div class="list-group">
                    @foreach($meetup->graded_quizzes as $key => $graded_quiz)
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h4 class="list-group-item-heading">{{ $graded_quiz->quiz_title }}</h4>
                                    <p class="list-group-item-text">{{ $graded_quiz->quiz_description }}</p>
                                    <p class="list-group-item-text">{{ $graded_quiz->quiz_type->name }}</p>
                                </div>
                                <div class="col-xs-6">
                                    @if(strpos($graded_quiz->quiz_type->name, 'Trivia') === false)
                                    <p><strong>Performance:</strong> {{ $quizzes_performance[$graded_quiz->id]['correct'] }}/{{ $quizzes_performance[$graded_quiz->id]['total_questions'] }} correct ({{ $quizzes_performance[$graded_quiz->id]['percentage'] }}%)</p>
                                    @endif
                                </div>
                            </div>
                            @if($quizzes_performance[$graded_quiz->id]['example'])
                            <div class="row m-t-15">
                                <div class="col-xs-12">
                                    @php $question = $graded_quiz->questions()->where('id', $quizzes_performance[$graded_quiz->id]['example']->id)->first() @endphp
                                    <strong>{{ $question->question_title or '' }}</strong>
                                    @if($question->question_photo)
                                        <img src="{{ $question->question_photo }}" class="img-responsive thumbnail m-t-5">
                                    @endif
                                    
                                    <div class="row answer-list m-t-10">
                                        @foreach($question->answers as $answer)
                                            <div class="col-xs-3 text-center answer-item">
                                                <div class="{{ ($answer->is_correct)? 'list-group-item-success' : '' }}">
                                                    @if($answer->original_photo)
                                                        <img src="{{ $answer->original_photo }}" class="img-responsive thumbnail m-b-5">
                                                    @endif
                                                    {{ $answer->text or '' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @if($student->graded_answer($question->id)->first())
                                        <div class="row answer-list m-t-5">
                                            <div class="col-xs-6 col-xs-offset-3 text-center answer-item">
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
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
        </div>
    </div>

@endsection
