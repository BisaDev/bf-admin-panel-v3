@section('page_title', 'Create minigame')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create minigame',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Minigames', 'url' => route('minigames.index')],
        ],
        'currentSection' => 'Create minigame',
    ])
@endsection

@section('content')
    <div class="row create-container" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('minigames.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Create a new Minigame</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Minigame Name:</label>
                            <input type="text" name="name" class="form-control">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('photo')? 'has-error' : '' }}">
                            <label class="control-label" for="photo">Screenshot:</label>
                            <div class="droppable">
                                <span v-if="!photo">Drag an image or click to browse</span>
                                <img v-else :src="photo" />
                                <input name="photo" type="file" @change="onFileChange">
                            </div>
                            @if($errors->has('photo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>

                        @include('partials.add-notes', ['help_text' => 'Use notes to describe mechanics, what type of quizzes work better with this minigame, etc.'])

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('minigames.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
