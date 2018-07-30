@extends('layouts.auth')

@section('page_title', 'Login')

@section('content')
    <div class="container">
        <div class="row">
            <div class="wrapper-page">
                <div class="panel-heading"><img src="{{ asset('images/logo.png') }}" class="img-responsive"/></div>
                <div class="panel-body">
                    <div class="form-group text-center">
                        <div class="alert alert-danger alert dismissible">
                            <h4>{{$errorMessage}}</h4>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-center">
                            <a href="{{ route('student_dashboard') }}" class="btn btn-md btn-info">Go to Students Login</a>
                            <a href="{{ route('login') }}" class="btn btn-md btn-info">Go back to Admin Login</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
