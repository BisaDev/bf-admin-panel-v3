@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Topics', 'url' =>  '/taggingtopics']
        ],
        'currentSection' => 'Create Tagging Topic',
    ])
@endsection

@section('content')
    <div id="app" class="container">
        <div class="card-box row">
            <form method="POST" action="/taggingtopics">
                {{csrf_field()}}
                <div class="form-group col-lg-12 {{ $errors->has('last_name')? 'has-error' : '' }}">
                    <label class="control-label" for="topic_name" style="min-width: 50%">
                        Topic:
                        <input type="text" name="topic_name" class="form-control">
                    </label>
                </div>
                <div class="form-group col-lg-2">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection