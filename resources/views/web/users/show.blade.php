@section('page_title', 'Employee details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Employee details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Employees', 'url' => route('employees.index')]
        ],
        'currentSection' => 'Employee details',
    ])
@endsection

@section('content')

    <div class="row" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Employee details</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2 col-sm-offset-5">
                                    <img src="{{ $item->user_detail->photo }}" class="img-responsive img-circle" alt="profile-image" data-pin-nopin="true">
                                </div>
                                <div class="col-sm-12 text-center">
                                    <h4 class="header-title">{{ $item->full_name }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12"><hr/></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="role">Role:</label>
                            <p>{{ ucfirst($item->roles->first()->name) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="location">Location:</label>
                            <p>{{ $item->user_detail->location->name }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="phone">Phone Number:</label>
                            <p>{{ $item->user_detail->phone }}</p>
                        </div>

                        <div class="col-md-6">
                            <label class="control-label" for="mobile_phone">Mobile Phone:</label>
                            <p>{{ $item->user_detail->mobile_phone }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="control-label" for="email">Email:</label>
                            <p>{{ $item->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="secondary_email">Secondary Email:</label>
                            <p>{{ $item->user_detail->secondary_email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
