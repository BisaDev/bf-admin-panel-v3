@section('page_title', 'Create question')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create question',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('questions.index')]
        ],
        'currentSection' => 'Create question',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-question" data-answers="{{ json_encode(old('answers')) }}">
        <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="col-md-10 col-md-offset-1">
                <div class="card-box">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('type')? 'has-error' : '' }}">
                            <label class="control-label" for="type">Type:</label>
                            <select id="type" name="type" class="form-control" v-model="type" @change="setDefaultQuestions">
                                <option value="">Select Type</option>
                                @foreach($types as $key => $type)
                                <option value="{{ $key }}" {{ (!is_null(old('type', $prefilled_fields['type'])) && (int)old('type', $prefilled_fields['type']) === $key)? 'selected' : '' }}>{{ $type }}</option>
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
                            <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ old('grade_level', $prefilled_fields['grade_level']) }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
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
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', $prefilled_fields['subject']) }}" @change="getTopicsFromSubject('{{ route('topics.by_subject') }}', $event)">
                            </select>
                            @if($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('topic')? 'has-error' : '' }}">
                            <label class="control-label" for="topic">Topic:</label>
                            <select id="topic" name="topic" class="form-control" data-selected="{{ old('topic', $prefilled_fields['topic']) }}">
                            </select>
                            @if($errors->has('topic'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('topic') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @include('partials.add-tags')

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-md-8 {{ $errors->has('title')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Question / Phrase:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                            <p class="text-muted" v-show="type == 1">Use [#blank] to specify where the blank space is in the phrase.<br/>e.g. 'Roses are [#blank], violets are blue'</p>
                            @if($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4 {{ $errors->has('photo')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Image</label>
                            <div class="droppable droppable-small">
                                <span v-if="!photo">Drag an image or click to browse</span>
                                <img v-else :src="photo" />
                                <input name="photo" type="file" @change="openCropImage($event, handleCrop, type)">
                            </div>
                            @if($errors->has('photo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row" v-show="type_has_canvas">
                        <div class="col-xs-12">
                            <p class="text-mute" v-if="!photo">Add question image to create canvas</p>
                            <p class="text-mute" v-else>Click on the canvas to add answer areas</p>
                            <canvas id="dnd-canvas"></canvas>
                        </div>
                    </div>

                    <div class="row" v-show="allows_answers">
                        @include('partials.add-answers')
                        @include('partials.add-answers-drag-drop')
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <input type="hidden" name="add_more">
                            <a href="{{ route('questions.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                            <button type="button" class="btn btn-md btn-primary" @click="saveQuestionAndAddMore">Create and add more</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
