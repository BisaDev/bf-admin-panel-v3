@extends('layouts.student_master')

@section('page_title', 'Analytics')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Analytics',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Analytics',
    ])
@endsection

@section('content')

    <div id="app" class="container">
        <div class="row">
            <div class="card-box col-md-12">
                I'm analytics
            </div>
        </div>
    </div>
@endsection
