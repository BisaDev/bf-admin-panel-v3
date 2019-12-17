@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Subjects', 'url' =>  '/taggingsubjects']
        ],
        'currentSection' => 'Create a tagging subject',
    ])
@endsection

@section('content')
    <div id="create-container" class="container">
        <div class="card-box row">
            <form method="POST" action="/taggingsubjects">

                {{csrf_field()}}

                <div class="form-group col-lg-12 {{ $errors->has('last_name')? 'has-error' : '' }}">
                    <label class="control-label" for="subject_name">
                        Subject:
                    </label>
                    <input type="text" name="subject_name" class="form-control">
                </div>

                <div class="form-group col-md-12 text-right m-t-30">
                    <a href="/taggingsubject" class="btn btn-md btn-info">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection