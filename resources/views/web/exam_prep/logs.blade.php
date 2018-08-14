@extends('layouts.master')

@section('page_title', 'Logs')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Logs',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
        ],
        'currentSection' => 'Logs',
    ])
@endsection

@section('content')
    <div id ="generate-results" class="row" data-exam-sections-url="{{ route('exams.sections_for_results') }}">
        <div class="col-md-12 card-box">
            <div class="row">
                <form action="{{ route('exams.generate_report') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="control-label" for="exam_id">Exam ID:</label>
                        <select id="exam_id" name="exam_id" class="form-control" v-model="exam_id"  @change="loadSections()">
                            <option value="">Select Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ (!is_null(old('exam_id')) && (int)old('exam_id') === $key)? 'selected' : '' }}>{{ $exam->test_id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-6 col-md-4">
                        <label class="control-label" for="section">Section ID</label>
                        <select id="section" name="section" class="form-control" v-model="section_id" data-selected="{{ old('section') }}" @change="loadSections()">
                            <option value="">Select Grade Level</option>
                            @foreach($sections as $key => $section)
                                <option value="{{ $key }}">{{ $key . ': ' . $section['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 text-right">
                        <button type="submit" class="btn btn-md btn-primary">Generate Results</button>
                    </div>
                </form>
            </div>
            <div class="row">
                <table class="table table-responsive table-hover model-list text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Time Completed</th>
                        <th class="text-center">Student Name</th>
                        <th class="text-center">Cohort</th>
                        <th class="text-center">Exam ID</th>
                        <th class="text-center">Section ID</th>
                        <th class="text-center">Raw Score</th>
                        <th class="text-center">Calculated Score</th>
                        <th class="text-center">Time Taken</th>
                        <th class="text-center">Generate Report</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($examSections as $section)
                        <tr v-for="section in examSections" v-if="section.id == {{$section->id}}" @click="toggleActive({{$section->id}})">
                            <td>{{ $section->created_at->format('d M Y') }}</td>
                            <td>{{ $section->studentExam->student->name }}</td>
                            <td>{{ $section->studentExam->student->cohort_tag }}</td>
                            <td>{{ $section->studentExam->exam->test_id }}</td>
                            <td>{{ $section->section_number }}</td>
                            <td>{{ $section->number_correct }} / {{$sections[$section->section_number]['questions']}}</td>
                            <td> - </td>
                            <td>{{ $section->time }} / {{$sections[$section->section_number]['timeAvailable']}}</td>
                            <td>
                                <input type="checkbox" name="{{'checkbox_' . $section->id}}" value="{{$section->id}}" v-model="checkedSections">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
