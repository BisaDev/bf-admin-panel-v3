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
        <div class="col-sm-12">
            <table class="table table-responsive table-hover model-list">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Exam Type</th>
                    <th>Section</th>
                    <th>Exam ID</th>
                    <th>Raw Score</th>
                    <th>Calculated Score</th>
                    <th>Time Used</th>
                    <th>Detailed Score Report</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                </tr>
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

                <div class="modal-body">
                    <div class="form-group">
                        <label for="exam-type">Select Exam Type:</label>
                        <select class="form-control" id="exam-type">
                            <option>SAT</option>
                            <option>Sciences</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="test-id">Enter Test ID:</label>
                        <input type="text" class="form-control" id="test-id">
                    </div>
                </div>

                <div v-show="examSection">
                    <label class="col-md-offset-2"> Exam Section: </label> <br>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <ul class="list-group">
                                <li class="list-group-item" v-for="section in sections">
                                    <div class="custom-control custom-checkbox">
                                        <label> <input type="checkbox" v-model="selected" :value="section.id"> @{{section.name}} </label>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <label><input type="checkbox" v-model="selectAll"> All sections </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" v-on:click="examSection = !examSection" v-if="!examSection" class="btn btn-md btn-info">Next</button>
                    <a href="{{ route('student_dashboard')}}" v-if="examSection" class="btn btn-md btn-info">Start Exam</a>
                </div>
            </div>
        </div>
    </div>

@endsection
