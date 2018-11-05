@section('page_title', 'CSV Question Importer')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'CSV Question Importer',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('questions.index')]
        ],
        'currentSection' => 'CSV Question Importer',
    ])
@endsection

@section('content')

    <div class="row create-exam" id="create-exam">
        <div class="col-md-8 col-md-offset-2">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('questions.store_csv') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Import Questions by CSV</h4>
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
