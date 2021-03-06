@extends('layouts.master')

@section('page_title', 'Create Exam')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create Exam',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' => route('exams.index')]
        ],
        'currentSection' => 'Create Exam',
    ])
@endsection

@section('content')

    <div class="row create-exam">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('exams.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Create a new Exam</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('csv')? 'has-error' : '' }}">

                            <upload-file id="upload-file"></upload-file>

                            @if($errors->all())
                                @foreach($errors->all() as $errorMessage)
                                    <span class="help-block">
                                        <strong>{{ $errorMessage}}</strong>
                                    </span>
                                @endforeach
                            @endif

                        </div>

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('exams.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
