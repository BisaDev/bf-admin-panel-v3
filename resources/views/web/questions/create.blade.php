@section('page_title', 'Create question')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create question',
        'link' => [ 'label' => 'CSV Question Importer', 'url' => route('questions.csv_importer')],
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
                            <textarea v-if="type == 0 && !equationImageShow" type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="600"></textarea>
                            <input v-else-if="type == 7" type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="250">
                            <input v-else type="text" name="title" class="form-control" value="{{ old('title') }}" maxlength="180">
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
                                <input type="hidden" name="photo_cropped" :value="photo" >
                            </div>
                            @if($errors->has('photo'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('photo') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div v-if="type == 7" class="form-group col-md-12 {{ $errors->has('other_photo_cropped')? 'has-error' : '' }}">
                            <label class="control-label" for="other_photo">Long Passage Image</label>
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

                        <div v-show="type === '0' || type === '1'">
                            <div class="form-group col-md-6">
                                <label class="control-label">Add Equation Image</label>
                                <input type="checkbox" name="add_equation" v-model="equationImageShow" data-old="{{ old('add_equation') }}" data-plugin="switchery" data-color="#FC7044" data-size="small" value="1"/>
                            </div>

                            <div v-if="equationImageShow">
                                <div class="form-group col-md-12 {{ $errors->has('other_photo_cropped')? 'has-error' : '' }}">
                                    <label class="control-label" for="other_photo">Equation Image</label>
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
                            </div>
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
                        <div class="col-md-4">
                            <a href="{{ route('questions.index') }}" class="btn btn-md btn-info">Cancel</a>
                        </div>
                        <div class="form-group col-md-8 text-right">
                            <input type="hidden" name="add_more">
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                            <button type="button" class="btn btn-md btn-primary" @click="saveQuestionAndAddMore">Create and add more</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
