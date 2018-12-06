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
                            <select id="type" name="type" class="form-control" v-model="type" @change="setDefaultQuestions">
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
                            <select id="grade_level" name="grade_level" class="form-control"
                                    data-selected="{{ old('grade_level', ($item->topic)? $item->topic->subject->grade_level->id : '') }}"
                                    @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
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
                            <select id="subject" name="subject" class="form-control"
                                    data-selected="{{ old('subject', ($item->topic)? $item->topic->subject->id : '') }}"
                                    @change="getTopicsFromSubject('{{ route('topics.by_subject') }}', $event)">
                            </select>
                            @if($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-3 {{ $errors->has('topic')? 'has-error' : '' }}">
                            <label class="control-label" for="topic">Topic:</label>
                            <select id="topic" name="topic" class="form-control"
                                    data-selected="{{ old('topic', ($item->topic)? $item->topic->id : '') }}">
                            </select>
                            @if($errors->has('topic'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('topic') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @include('partials.add-tags', ['tags' => $item->tags])

                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-8 {{ $errors->has('title')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Question / Phrase:</label>
                            @if($item->type->key == 0 && !$item->other_photo)
                                <textarea name="title" class="form-control" maxlength="600">{{ $item->title}}</textarea>
                            @elseif($item->type->key == 7)
                                <input type="text" name="title" class="form-control" value="{{ $item->title }}" maxlength="250">
                            @else
                                <input type="text" name="title" class="form-control" value="{{ $item->title }}" maxlength="180">
                            @endif
                            <p class="text-muted" v-show="type == 1">Use [#blank] to specify where the blank space is in
                                the phrase.<br/>e.g. 'Roses are [#blank], violets are blue'</p>
                            @if($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4 {{ $errors->has('photo')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Image</label>
                            @if($item->photo)
                                <div class="col-xs-12 m-b-10 text-center" v-show="!type_has_canvas">
                                    <img src="{{ $item->photo }}" id="question_photo"
                                         class="img-responsive center-block">
                                </div>
                            @endif
                            <div class="droppable droppable-small">
                                <span v-if="!photo">Drag an image or click to browse</span>
                                <img v-else :src="photo"/>
                                <input name="photo" type="file" @change="openCropImage($event, handleCrop, type)">
                                <input type="hidden" name="photo_cropped" :value="photo">
                            </div>
                            @if($errors->has('photo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>
                        @if($item->other_photo != '')
                            <div class="form-group col-md-12 {{ $errors->has('photo')? 'has-error' : '' }}">
                                @if($item->type->key == 7)
                                    <label class="control-label" for="title">Long Passage Image</label>
                                @else
                                    <label class="control-label" for="title">Equation Image</label>
                                @endif
                                <div class="col-xs-12 m-b-10 text-center">
                                    <img v-show="!delete_other_photo" src="{{ $item->other_photo }}" class="img-responsive center-block">
                                    <button v-show="!delete_other_photo" type="button" class="btn btn-mid btn-danger" @click="confirmDelete('other_photo')">Delete Image</button>
                                    <input type="hidden" name="delete_other_photo" :value="delete_other_photo">
                                </div>
                                <div class="droppable droppable-small">
                                    <span v-if="!other_photo">Drag an image or click to browse</span>
                                    <img v-else :src="other_photo"/>
                                    <input name="other_photo" type="file" @change="uploadLongPassageImage($event)">
                                    <input type="hidden" name="other_photo_cropped" :value="other_photo">
                                </div>
                                @if($errors->has('other_photo_cropped'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('other_photo_cropped') }}</strong>
                                </span>
                                @endif
                            </div>
                        @endif
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

                    <div class="row form-group" v-if="type === '0' || type === '7'">
                        <div class="col-md-6">
                            <label class="control-label" for="answer_explanation">Answer Explanation</label>
                            <textarea class="form-control" name="answer_explanation" placeholder="Answer Explanation">{{ $item->answer_explanation }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="control-label" for="answer_explanation_photo">Answer Explanation Image</label>
                            @if($item->answer_explanation_photo != '')
                                <div class="col-md-12 m-b-10">
                                    <img src="{{ $item->answer_explanation_photo }}" class="img-responsive center-block">
                                </div>
                            @endif
                            <div class="droppable droppable-small">
                                <span v-if="!answer_explanation_photo">Drag an image or click to browse</span>
                                <img v-else :src="answer_explanation_photo"/>
                                <input name="answer_explanation_photo" type="file" @change="uploadAnswerExplanationImage($event)">
                                <input type="hidden" name="answer_explanation_photo_cropped" :value="answer_explanation_photo">
                            </div>
                        </div>
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
