@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create topic',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Topics', 'url' =>  route('students.create')]
        ],
        'currentSection' => 'Create Tagging Topic',
    ])
@endsection

@section('content')
    <div id="app" class="container">
        <div class="card-box row">
            <form>
                <div class="form-group col-lg-6 {{ $errors->has('last_name')? 'has-error' : '' }}">
                    <label class="control-label" for="topic_name">Topic:
                    </label>
                    <input type="text" name="topic_name" class="form-control">
                </div>
                <div class="form-group col-lg-6  {{ $errors->has('last_name')? 'has-error' : '' }}">
                    <label class="control-label" for="topic_dude">Dude:
                    </label>
                    <input type="text" name="topic_dude" class="form-control">
                </div>
                <div class="form-group col-lg-2">
                    <button type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection