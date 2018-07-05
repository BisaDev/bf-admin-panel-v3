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

    <div class="modal fade" id="take-practice-exam">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"> Take Practice Exam</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="test-id">Enter Test ID:</label>
                        <input type="text" class="form-control" id="test-id">
                    </div>
                    <div class="form-group">
                        <label for="exam-type">Select Exam Type:</label>
                        <select class="form-control" id="exam-type">
                            <option>SAT</option>
                            <option>Sciences</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <a href="#" class="btn btn-md btn-info" data-toggle="modal"
                       data-target="#take-practice-exam-section">Next</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="take-practice-exam-section">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">SAT Exam Selection</h4>
                </div>

                <div class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Reading Comprehension</td>
                                        <td><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>Writing and Language</td>
                                        <td><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>Math No Calculator</td>
                                        <td><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>Math With Calculator</td>
                                        <td><input type="checkbox"></td>
                                    </tr>
                                    <tr>
                                        <td>All sections</td>
                                        <td><input type="checkbox"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('dashboard') }}" class="btn btn-md btn-info">Start Exam</a>
                </div>
            </div>
        </div>
    </div>

@endsection
