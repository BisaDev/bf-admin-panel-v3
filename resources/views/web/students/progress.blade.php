@section('page_title', 'Student progress')
@extends('layouts.print')

@section('content')
    
    <div class="row create-container" id="create-meetup">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-6">
                    <img src="{{ asset('images/logo.png') }}" class="print-logo" />
                </div>
                <div class="col-xs-6 text-right">
                    <div class="row">
                        <div class="col-xs-6 col-xs-offset-4">
                            <h1 class="m-b-20">Progress report</h1>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12"><hr/></div>
            </div>
            
            <div class="row m-b-20">
                <div class="col-xs-2">
                    <img src="{{ $student->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                </div>
                <div class="col-xs-10">
                    <h2>{{ $student->full_name }}</h2>
                    <h2>{{ $student->school_year }}, {{ $student->current_school }}</h2>
                </div>
            </div>
    
            <div class="row">
                <div class="col-xs-12"><hr/></div>
            </div>
    
            <div class="row m-b-20">
                <div class="col-xs-6">
                    <h4>Brightfox member since {{ $student->created_at->format('F jS, Y') }}</h4>
                    <h4>{{ $meetup_hours['total'] }} total hours completed</h4>
                    <h4>{{ $total_questions }} questions</h4>
                </div>
                <div class="col-xs-6">
                    @foreach($meetup_hours['subjects'] as $subject => $hours)
                        <h4>{{ round((100 * $hours)/$meetup_hours['total']) }}% of total hours in {{ $subject }}</h4>
                    @endforeach
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12">
                    <h3>Student Notes</h3>
                </div>
                <div class="col-xs-12">
                    <table class="table table-responsive table-hover model-list">
                        <tbody>
                        @foreach($student->meetups as $meetup)
                            @if($student->meetup_student_pivot($meetup->id)->first()->notes->count() > 0)
                            @foreach($student->meetup_student_pivot($meetup->id)->first()->notes as $note)
                            <tr>
                                <td>{{ $note->title }}</td>
                                <td>{{ $note->text }}</td>
                            </tr>
                            @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    
            @foreach($student_data as $subject => $subject_data)
            <div class="row">
                <div class="col-xs-12">
                    <h3>{{ $subject }}</h3>
                </div>
                @foreach($subject_data as $topic => $topic_data)
                <div class="col-xs-12">
                    <h4>{{ $topic }}</h4>
                </div>
                <div class="col-xs-12">
                    <table class="table table-responsive table-hover model-list">
                        <thead>
                            <th>Grade Level</th>
                            <th>Total Questions</th>
                            <th>% Correct</th>
                            <th>Status</th>
                            <th>Date of Mastery</th>
                        </thead>
                        <tbody>
                            @foreach($topic_data as $level => $level_data)
                            <tr>
                                <td>{{ $level }}</td>
                                <td>{{ $level_data['total_questions'] }}</td>
                                <td>{{ $level_data['percentage_correct'] }}</td>
                                <td>{{ ($level_data['date_mastered'] != 'N/A')? 'Mastered' : ($level_data['total_questions'] > 0)? 'In progress' : 'Not started' }}</td>
                                <td>{{ $level_data['date_mastered'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </div>

@endsection
