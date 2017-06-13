@section('page_title', 'Employee details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => $item->full_name,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Employees', 'url' => route('employees.index')]
        ],
        'currentSection' => $item->full_name,
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <img src="{{ $item->user_detail->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                            </div>
                            <div class="col-xs-12 text-center">
                                <h4 class="header-title">{{ $item->full_name }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12"><hr/></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Role:</label>
                        <p>{{ ucfirst($item->roles->first()->name) }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Location:</label>
                        <p>{{ (!is_null($item->user_detail->location))? $item->user_detail->location->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Phone Number:</label>
                        <p>{{ $item->user_detail->phone }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="control-label">Mobile Phone:</label>
                        <p>{{ $item->user_detail->mobile_phone }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label">Email:</label>
                        <p>{{ $item->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="control-label">Secondary Email:</label>
                        <p>{{ $item->user_detail->secondary_email }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('employees.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('employees.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
