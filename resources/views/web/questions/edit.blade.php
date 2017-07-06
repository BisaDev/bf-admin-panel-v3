@section('page_title', 'Edit question')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit question',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('questions.index')]
        ],
        'currentSection' => 'Edit question',
    ])
@endsection

@section('content')
    
    <div class="row create-container" id="create-question" data-answers="{{ json_encode($item->answers) }}">
        <form action="{{ route('questions.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="col-md-10 col-md-offset-1">
                <div class="card-box">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('type')? 'has-error' : '' }}">
                            <label class="control-label" for="type">Type:</label>
                            <select id="type" name="type" class="form-control" v-model="type">
                                <option value="">Select Type</option>
                                @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ (!is_null(old('type', $item->type->key)) && (int)old('type', $item->type->key) === $key)? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('grade_level')? 'has-error' : '' }}">
                            <label class="control-label" for="grade_level">Grade Level:</label>
                            <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ old('grade_level', ($item->topic)? $item->topic->subject->grade_level->id : '') }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
                                <option value="">Select Grade Level</option>
                                @foreach($grade_levels as $grade_level)
                                <option value="{{ $grade_level->id }}">{{ ucfirst($grade_level->name) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('grade_level'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('grade_level') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('subject')? 'has-error' : '' }}">
                            <label class="control-label" for="subject">Subject:</label>
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', ($item->topic)? $item->topic->subject->id : '') }}" @change="getTopicsFromSubject('{{ route('topics.by_subject') }}', $event)">
                            </select>
                            @if($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('topic')? 'has-error' : '' }}">
                            <label class="control-label" for="topic">Topic:</label>
                            <select id="topic" name="topic" class="form-control" data-selected="{{ old('topic', ($item->topic)? $item->topic->id : '') }}">
                            </select>
                            @if($errors->has('topic'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('topic') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @include('partials.add-tags', ['tags' => $item->tags])

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-md-8 {{ $errors->has('title')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Question / Phrase:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
                            <p class="text-muted" v-show="type == 1">Use [#blank] to specify where the blank space is in the phrase.<br/>e.g. 'Roses are [#blank], violets are blue'</p>
                            @if($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4 {{ $errors->has('photo')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Image</label>
                            @if($item->photo)
                            <div class="col-xs-12 m-b-10 text-center">
                                <img src="{{ $item->photo }}" class="img-responsive center-block">
                            </div>
                            @endif
                            <div class="droppable droppable-small">
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
                    </div>

                    <div class="row">
                        @include('partials.add-answers')
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('questions.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
