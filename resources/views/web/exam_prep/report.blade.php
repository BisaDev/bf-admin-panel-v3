@extends('layouts.master')

@section('page_title', 'Detailed Report')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Detailed Report',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Logs', 'url' =>  route('exams.logs')]
        ],
        'currentSection' => 'Detailed Report',
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 card-box">
        </div>
    </div>
@endsection
