@extends('layouts.student_master')

@section('page_title', 'Dashboard')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Dashboard',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Dashboard',
    ])
@endsection

@section('content')
    <div class="row" id="student-dashboard">
        <div class="col-sm-12 card-box">
            <form action="{{ route('student_dashboard') }}" method="POST">
                {{ csrf_field() }}
                <div class="row student-dashboard-filters">
                    <div class="col-md-3">
                        <label v-show="!showAllExams" for="showAllExams">Show All Exams</label>
                        <label v-show="showAllExams" for="showAllExams">Show Completed Exams</label>
                        <input type="checkbox" name="showAllExams" v-model="showAllExams" data-plugin="switchery"
                               data-color="#FC7044" data-size="small">
                    </div>
                    <div class="col-md-3">
                        <label for="examType">Filter By Exam Type</label>
                        <select name="examType" class="form-control">
                            <option value="">All Types</option>
                            @foreach($examTypes as $examType)
                                <option value="{{$examType}}" {{$typeSelected === $examType ? 'selected' : ''}}>{{$examType}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 m-t-20">
                        <button type="submit" class="btn btn-md btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <table class="table table-responsive table-hover model-list text-center" v-show="!showAllExams">
                <thead>
                <tr>
                    <th class="text-center">Date</th>
                    <th class="text-center">Exam Type</th>
                    <th class="text-center">Section</th>
                    <th class="text-center">Exam ID</th>
                    <th class="text-center">Raw Score</th>
                    <th class="text-center">Calculated Score</th>
                    <th class="text-center">Time Used</th>
                    <th class="text-center">Detailed Score Report</th>
                </tr>
                </thead>
                <tbody class="bg-exams-hover">
                @foreach($studentExams as $exam)
                    <tr>
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                            class="clickable">{{ $exam->created_at->format('d M Y') }}</td>
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                            class="clickable">{{$exam->exam->type}}</td>
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">
                                <span>
                                    Sections:
                                    @foreach($exam->sections->unique('section_number') as $section)
                                        {{$section->section_number}}
                                    @endforeach
                                </span>
                        </td>
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                            class="clickable">{{$exam->exam->test_id}}</td>
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                            class="clickable">{{$exam->number_correct}}
                            / {{ $exam->exam->IsMiniExam ? $exam->exam->mini_exam_questions : $exam->TotalQuestions }}</td>
                        @if($exam->score)
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                                class="clickable">{{$exam->score}}</td>
                        @else
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable"><span
                                        data-toggle="tooltip" data-placement="right"
                                        title="You will have a Calculated Score when you finish all Exam Sections">{{$exam->score}}</span>
                            </td>
                        @endif
                        <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}"
                            class="clickable"> {{$exam->time}}
                            / {{ $exam->exam->IsMiniExam ? $exam->exam->mini_exam_time : $exam->TotalTimeAvailable}} </td>
                        <td><a href="{{ route('answer_sheet.show_results', $exam->id) }}">View</a></td>
                    </tr>
                    @foreach($exam->sections as $section)
                        <tr class="bg-exams-dashboard collapse accordion_{{$exam->id}}">
                            <td>{{ $exam->created_at->format('d M Y') }}</td>
                            <td></td>
                            <td>{{ $exam->exam->IsMiniExam ? '-' : $section->metadata->section_name }}</td>
                            <td></td>
                            <td>{{$section->number_correct}}
                                / {{ $exam->exam->IsMiniExam ? $exam->exam->mini_exam_questions : $section->metadata->questions }}</td>
                            @if($section->score)
                                @if($section->section_number == 3 || $section->section_number == 4)
                                    <td><span data-toggle="tooltip" data-placement="right"
                                              title="Both Math sections have the same score but only one is considered in the overall exam score">{{$section->score}}</span>
                                    </td>
                                @else
                                    <td>{{$section->score}}</td>
                                @endif
                            @else
                                <td><span data-toggle="tooltip" data-placement="right"
                                          title="You will have a Calculated Score when you complete both Math Sections">{{$section->score}}</span>
                                </td>
                            @endif
                            <td> {{$section->time}}
                                / {{ $exam->exam->IsMiniExam ? $exam->exam->mini_exam_time : $section->metadata->time_available }} </td>
                            <td></td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

            <table class="table table-responsive table-hover model-list text-center" v-show="showAllExams">
                <thead>
                <tr>
                    <th class="text-center">Exam Type</th>
                    <th class="text-center">Exam ID</th>
                    <th class="text-center">Source</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Sections</th>
                    <th class="text-center">Completed</th>
                </tr>
                </thead>
                <tbody>
                @foreach($exams as $exam)
                    <tr>
                        <td>{{$exam->type}}</td>
                        <td>{{$exam->test_id}}</td>
                        <td>{{$exam->source}}</td>
                        <td>{{$exam->description}}</td>
                        <td>{{$exam->numberOfSections}}</td>
                        @if($exam->isCompleted)
                            <td><span class="badge badge-pill badge-success"><i class="ti-check"></i></span></td>
                        @else
                            <td> -</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div id="take-practice-exam" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Take Practice Exam</h4>
                </div>

                <form action="{{ route('answer_sheet.create_exam') }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <take-practice-exam :exams="{{$allExams}}" :exam-types="{{$allExams->unique('type')}}"
                                        :all-sections="{{$allSections}}"
                                        :exams-completed="{{$examsCompleted}}"></take-practice-exam>
                </form>
            </div>
        </div>
    </div>

@endsection
