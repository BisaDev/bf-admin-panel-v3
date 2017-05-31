@extends('layouts.master')

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
        
    </div>
</div>
@endsection
