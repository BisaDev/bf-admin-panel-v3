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
    <div class="row">
        <div class="col-sm-12 card-box">
            <table class="table table-responsive model-list text-center">
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
                <tbody>
                    @foreach($student->exams as $exam)
                        <tr>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">{{ $exam->created_at->format('d M Y') }}</td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">{{$exam->exam->type}}</td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">
                                <span>
                                    Sections:
                                    @foreach($exam->sections->unique('section_number') as $section)
                                        {{$section->section_number}}
                                    @endforeach
                                </span>
                            </td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">{{$exam->exam->test_id}}</td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable">{{$exam->number_correct}} / {{ array_sum(array_column($allSections, 'questions')) }}</td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable"> {{$exam->score}} </td>
                            <td data-toggle="collapse" data-target=".accordion_{{$exam->id}}" class="clickable"> {{$exam->time}} / {{ array_sum(array_column($allSections, 'timeAvailable')) }} </td>
                            <td><a href="{{ route('answer_sheet.show_results', $exam->id) }}">View</a></td>
                        </tr>
                        @foreach($exam->sections as $section)
                            <tr class="bg-exams-dashboard collapse accordion_{{$exam->id}}">
                                <td>{{ $exam->created_at->format('d M Y') }}</td>
                                <td></td>
                                <td>{{$allSections[$section->section_number]['name']}}</td>
                                <td></td>
                                <td>{{$section->number_correct}} / {{$allSections[$section->section_number]['questions']}}</td>
                                <td>{{$section->score}}</td>
                                <td> {{$section->time}} / {{$allSections[$section->section_number]['timeAvailable']}} </td>
                                <td></td>
                            </tr>
                        @endforeach
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
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exam-type">Select Exam Type:</label>
                            <select class="form-control" name="exam-type" id="exam-type">
                                @foreach($exams->unique('type') as $exam)
                                    <option>{{$exam->type}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="test-id">Enter Test ID:</label>
                            <select class="form-control" name="test-id" id="test-id">
                                @foreach($exams as $exam)
                                    <option>{{$exam->test_id}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div v-show="examSection">
                            <label class="col-md-offset-2"> Exam Section: </label> <br>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <ul class="list-group">
                                        <li class="list-group-item" v-for="section in sections">
                                            <div class="custom-control custom-checkbox">
                                                <label> <input type="checkbox" v-model="selected" :name="'sections[]'" :value="section.id"> @{{section.name}} </label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <label><input type="checkbox" v-model="selectAll"> All sections </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" v-on:click="examSection = !examSection" v-if="!examSection" class="btn btn-md btn-info">Next</button>
                        <button type="submit" v-if="examSection" class="btn btn-md btn-info">Start Exam</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
