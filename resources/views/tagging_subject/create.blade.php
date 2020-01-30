@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Subjects', 'url' =>  route('taggingsubjects.index')]
        ],
        'currentSection' => 'Create a tagging subject',
    ])
@endsection

@section('content')
    <div class="container" id="create-container">
        <div class="card-box row">
            <form method="POST" action="{{route('taggingsubjects.store')}}">

                {{csrf_field()}}
                <div class="form-group col-lg-12 {{ $errors->has('last_name')? 'has-error' : '' }}">
                    <label class="control-label" for="name">
                        Subject:
                    </label>
                    <input type="text" name="name" class="form-control">
                </div>

                @include('partials.add-topics')

                <div class="form-group col-md-12 text-right m-t-30">
                    <a href="{{route('taggingsubjects.index')}}" class="btn btn-md btn-info">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
@endsection