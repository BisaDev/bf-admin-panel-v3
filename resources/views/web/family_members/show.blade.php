@section('page_title', 'Family member details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => $item->full_name,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')],
            [ 'label' => $item->student->full_name, 'url' => route('students.show', $item->student->id)],
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
                                <img src="{{ $item->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                            </div>
                            <div class="col-xs-12 text-center">
                                <h2>{{ $item->full_name }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12"><hr/></div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Address</label>
                        <address>{{ $item->address }}<br/>{{ $item->city }}, {{ $item->state }}</address>
                    </div>
                    <div class="col-md-6">
                        <label>Can pick up?</label>
                        <p>{{ $item->can_pickup? 'Yes' : 'No' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Phone Number:</label>
                        <p>{{ $item->phone }}</p>
                    </div>

                    <div class="col-md-6">
                        <label>Mobile Phone:</label>
                        <p>{{ $item->mobile_phone }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label>Email:</label>
                        <p>{{ $item->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Secondary Email:</label>
                        <p>{{ $item->secondary_email }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('students.show', $item->student->id) }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('family_members.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
