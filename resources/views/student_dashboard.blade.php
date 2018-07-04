@extends('layouts.students_master')

@section('page_title', 'Dashboard')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Dashboard',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
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
@endsection
