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
    <div class="row">
        <div class="col-md-12 card-box">
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
                    <tr>
                        <td>{{ $section->created_at->format('d M Y') }}</td>
                        <td>{{ $section->studentExam->student->name }}</td>
                        <td>{{ $section->studentExam->student->cohort_tag }}</td>
                        <td>{{ $section->studentExam->exam->test_id }}</td>
                        <td>{{ $section->section_number }}</td>
                        <td>{{ $section->number_correct }}</td>
                        <td> - </td>
                        <td>{{ $section->time }}</td>
                        <td>
                            <input type="checkbox" name="{{'checkbox_' . $section->id}}" value="1">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
