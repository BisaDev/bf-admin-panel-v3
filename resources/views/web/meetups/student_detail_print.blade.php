@section('page_title', 'Meetup Detail')
@extends('layouts.print')

@section('content')
    <div class="container-alt">
        {{-- PAGE HEADER --}}
        <div class="row">
            <div class="col-xs-6">
                <h1>Meetup Detail</h1>
                <p class="subhead">{{ $meetup->start_time->format('F jS, Y') }} {{ $meetup->start_time->format('g:i') }} - {{ $meetup->end_time->format('g:i a') }}</p>
            </div>
            <div class="col-xs-6 text-right">
                <img src="{{ asset('images/logo.png') }}" class="print-logo" />
            </div>
        </div>

        {{-- STUDENT AND TEACHER ROW --}}
        <div class="row print-section student-instructor-details">
            <div class="col-xs-2">
                <img src="{{ $student->photo }}" class="img-circle student-photo">
            </div>
            <div class="col-xs-4 student-details">
                <h2>{{ $student->full_name }}</h2>
                <p>{{ $student->school_year }}</p>
                <p>{{ $student->current_school }}</p>
            </div>
            <div class="col-xs-2 col-xs-offset-1">
                @if($meetup->user)
                    <img src="{{ $meetup->user->user_detail->photo }}" class="img-circle profile-photo">
                @endif
            </div>
            <div class="col-xs-3">
                <h3>Instructor</h3>
                @if($meetup->user)
                    <p class="subhead">{{ $meetup->user->full_name }}</p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12"><hr/></div>
        </div>
    
        {{-- CLASSMATES ROW --}}
        <div class="row print-section">
            <div class="col-xs-12">
                <h3 class="m-b-40">{{ $student->name }}'s classmates for today</h3>
            </div>
            @foreach($meetup->students as $classmate)
                @if($student->id != $classmate->id)
                <div class="text-center col-xs-3">
                    <img src="{{ $classmate->photo }}" class="img-circle profile-photo">
                    <p class="m-t-5">{{ $classmate->full_name }}</p>
                </div>
                @endif
            @endforeach
        </div>
    </div>
        
    <div class="container-fluid meetup-notes">
        {{-- NOTES ROW --}}
        <div class="container-alt">
            <div class="row print-section">
                <div class="col-xs-12">
                    <h3 class="m-b-40">Notes about the student for this meetup</h3>
                </div>
                @if($student->meetup_student_pivot($meetup->id)->first()->notes->count() > 0)
                    <div class="col-xs-12">
                        @foreach($student->meetup_student_pivot($meetup->id)->first()->notes as $note)
                            <p>{{ $note->text }}</p>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="container-alt">
        {{-- RESULTS HEADER --}}
        <div class="row print-section results-header">
            <div class="col-xs-12">
                <h3 class="m-b-30">This day activities results</h3>
            </div>
            <div class="col-xs-6">
                <p class="subhead">Activity subject for the day: {{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->name : '' }}</p>
            </div>
            <div class="col-xs-6 text-right">
                <p class="subhead">Activities grade level: {{ ($meetup->activity_bucket->subject)? $meetup->activity_bucket->subject->grade_level->name : '' }}</p>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <hr/>
    </div>

    <div class="container-alt">
        @foreach($meetup->graded_quizzes as $key => $graded_quiz)
        <div class="row print-section quiz-header">
            <div class="col-xs-6">
                <p class="subhead"><strong>{{ $graded_quiz->quiz_title }} quiz</strong></p>
            </div>
            <div class="col-xs-6 text-right performance-chart">
                @if(strpos($graded_quiz->quiz_type->name, 'Trivia') === false)
                <p class="performance">{{ $quizzes_performance[$graded_quiz->id]['correct'] }}/{{ $quizzes_performance[$graded_quiz->id]['total_questions'] }} Correct</p>
                <input class="knob" data-width="80" data-height="80" data-linecap=round data-fgColor="#FC7044" value="{{ $quizzes_performance[$graded_quiz->id]['percentage'] }}" data-angleOffset="180" data-readOnly=true data-thickness=".15"/>
                @endif
            </div>
        </div>
        @if($quizzes_performance[$graded_quiz->id]['example'])
        <div class="row m-t-20">
            @php $question = $graded_quiz->questions()->where('id', $quizzes_performance[$graded_quiz->id]['example']->id)->first() @endphp
            <div class="col-xs-12">
                <p class="subhead">Control question: <span>{{ $question->question_title or '' }}</span></p>
                <div class="row">
                    <div class="col-xs-3">
                        @if($question->question_photo && $graded_quiz->quiz_type->name != 'Apple pencil')
                            <img src="{{ $question->question_photo }}" class="img-responsive">
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="row answer-list">
                    @php $student_answer = $student->graded_answer($question->id)->first() @endphp
                    @foreach($question->answers as $answer)
                        <div class="col-xs-3 answer-item">
                            <span class="status-icon-container">
                                @if($student_answer && ($answer->id == $student_answer->answer_id) && !$student_answer->is_correct)
                                    <img src="{{ asset('images/icon-notok.png') }}" /> {{ $student->name.' answered' }}
                                @elseif($answer->is_correct)
                                    <img src="{{ asset('images/icon-ok.png') }}" /> {{ 'Correct answer' }}
                                @else
                                    {{ '&nbsp;' }}
                                @endif
                            </span>
                            @if($answer->original_photo)
                                <img src="{{ $answer->original_photo }}" class="img-responsive">
                            @endif
                            <div class="text-center {{ ($answer->is_correct)? 'answer-correct' : (($student_answer && ($answer->id == $student_answer->answer_id) && !$student_answer->is_correct)? 'answer-incorrect' : '') }}">
                                {{ $answer->text or '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    
        @if(!$loop->last)
        <div class="row">
            <div class="col-xs-12"><hr/></div>
        </div>
        @endif
        @endforeach
    </div>

@endsection
