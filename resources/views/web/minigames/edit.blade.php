@section('page_title', 'Edit minigame')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit minigame',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Minigames', 'url' => route('minigames.index')],
        ],
        'currentSection' => 'Edit minigame',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container" data-notes="{{ $item->notes->toJson() }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('minigames.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('put') }}

                        <div class="col-sm-12">
                            <h4 class="header-title m-b-30">Edit Minigame</h4>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Minigame Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ $item->name }}">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('photo')? 'has-error' : '' }}">
                            <label class="control-label" for="photo">Screenshot:</label>
                            <div class="col-xs-12 m-b-10 text-center">
                                <img src="{{ $item->photo }}" class="img-responsive center-block">
                            </div>
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
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
