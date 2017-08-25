@section('page_title', 'Student progress')
@extends('layouts.print')

@section('content')
    <div class="container-alt">
        {{-- PAGE HEADER --}}
        <div class="row">
            <div class="col-xs-6">
                <h1>Progress Report</h1>
                @php $today = Carbon\Carbon::today(); @endphp
                <p class="subhead">{{ $today->format('F jS, Y') }}</p>
            </div>
            <div class="col-xs-6 text-right">
                <img src="{{ asset('images/logo.png') }}" class="print-logo" />
            </div>
        </div>
    </div>

    <div class="student-data-section">
        <div class="container-alt">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-2">
                    <h2>{{ $student->full_name }}</h2>
                </div>
            </div>
        </div>
        <div class="container-fluid meetup-notes">
            <div class="container-alt">
                <div class="row">
                    <div class="col-xs-2 student-details">
                        <img src="{{ $student->photo }}" class="student-photo img-circle progress-photo">
                        <p>&nbsp;</p>
                        <p>&nbsp;</p>
                    </div>
                    <div class="col-xs-4 student-details">
                        <p>{{ $student->school_year }}</p>
                        <p>{{ $student->current_school }}</p>
                    </div>
                    <div class="col-xs-6 student-details">
                        <p>Brightfox Member Since: <span>{{ $student->created_at->format('F jS, Y') }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="container-alt">
        <div class="row print-section results-header">
            <div class="col-xs-6">
                <h3>Total hours</h3>
            </div>
            <div class="col-xs-6">
                <h3>Spent hours by subject</h3>
            </div>
        </div>
        <div class="row print-section">
            <div class="col-xs-6">
                <p class="total-hours">
                    {{ $meetup_hours['total'] }} <span class="hours">hr</span> <span class="at">at</span>
                    <img src="{{ asset('images/logo.png') }}" class="print-logo small" />
                </p>
                <p class="total-questions"><span>{{ $total_questions }}</span> Questions answered</p>
            </div>
            <div class="col-xs-6">
                @foreach($meetup_hours['subjects'] as $subject => $hours)
                    <h4>{{ round((100 * $hours)/$meetup_hours['total']) }}% of total hours in {{ $subject }}</h4>
                @endforeach
            </div>
        </div>
        </div>
    </div>
    
    @foreach($student_data as $subject => $subject_data)
        @foreach($subject_data as $topic => $topic_data)
            <div class="subject-data-container">
                <div class="container-alt">
                    <div class="row">
                        <div class="col-xs-12">
                            <h3>Subject: <span class="gray">{{ $subject }} /</span> <span class="topic">Topic: <span class="gray">{{ $topic }}</span></span></h3>
                        </div>
                    </div>
                </div>
                <div class="progress-table">
                    <div class="container-alt">
                        <div class="row">
                            <div class="col-xs-2 five-columns">Grade Level</div>
                            <div class="col-xs-2 five-columns">Total Questions</div>
                            <div class="col-xs-2 five-columns">Correct %</div>
                            <div class="col-xs-2 five-columns">Mastering Status</div>
                            <div class="col-xs-2 five-columns">Mastering Date</div>
                        </div>
                    </div>
                    <div class="progress-table-body">
                        @foreach($topic_data as $level => $level_data)
                            <div class="container-fluid data-row">
                                <div class="container-alt">
                                    <div class="row">
                                        <div class="col-xs-2 five-columns">{{ $level }}</div>
                                        <div class="col-xs-2 five-columns">{{ $level_data['total_questions'] }}</div>
                                        <div class="col-xs-2 five-columns">{{ $level_data['percentage_correct'] }}</div>
                                        <div class="col-xs-2 five-columns status-icon-container">
                                            @if($level_data['date_mastered'] != 'N/A')
                                                <img src="{{ asset('images/icon-ok.png') }}" /> {{ 'Mastered' }}
                                            @elseif($level_data['total_questions'] > 200 && $level_data['date_mastered'] == 'N/A')
                                                <img src="{{ asset('images/icon-notok.png') }}" />{{ 'Not mastered' }}
                                            @elseif($level_data['total_questions'] < 200)
                                                {{ 'In progress' }}
                                            @else
                                                {{ 'Not started' }}
                                            @endif
                                        </div>
                                        <div class="col-xs-2 five-columns">{{ $level_data['date_mastered'] }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

@endsection
