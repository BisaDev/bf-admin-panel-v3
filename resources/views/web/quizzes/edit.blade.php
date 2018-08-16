@section('page_title', 'Edit quiz')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit quiz',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Quizzes', 'url' => route('quizzes.index')]
        ],
        'currentSection' => 'Edit quiz',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz" data-questions="{{ json_encode($item->questions) }}" data-questions-url="{{ route('questions.for_quiz') }}">
        <form action="{{ route('quizzes.update', $item->id) }}" method="POST">
            {{ csrf_field() }}
            {{ method_field('put') }}

            <div class="col-md-10 col-md-offset-1">
                <div class="card-box">
                    <div class="row">
                        <div class="form-group col-xs-12 {{ $errors->has('title')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Title:</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $item->title) }}">
                            @if($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-xs-12 {{ $errors->has('description')? 'has-error' : '' }}">
                            <label class="control-label" for="title">Description</label>
                            <textarea name="description" class="form-control">{{ old('description', $item->description) }}</textarea>
                            @if($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    @include('partials.add-tags', ['tags' => $item->tags])

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-sm-6 col-md-4 {{ $errors->has('type')? 'has-error' : '' }}">
                            <label class="control-label" for="type">Type:</label>
                            <select id="type" name="type" class="form-control" v-model="type" @change="loadQuestions('{{ route('questions.for_quiz') }}', $event)">
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
                        <div class="form-group col-sm-6 col-md-4 {{ $errors->has('grade_level')? 'has-error' : '' }}">
                            <label class="control-label" for="grade_level">Grade Level:</label>
                            <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ old('grade_level', ($item->subject)? $item->subject->grade_level->id : '') }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
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
                        <div class="form-group col-sm-6 col-md-4 {{ $errors->has('subject')? 'has-error' : '' }}">
                            <label class="control-label" for="subject">Subject:</label>
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', ($item->subject)? $item->subject->id : '') }}" v-model="subject" @change="loadQuestions('{{ route('questions.for_quiz') }}', $event)">
                            </select>
                            @if($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
    
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-4">
                            <input type="text" name="created_at" class="form-control datepicker-general" placeholder="Created Date" v-model="created_at" >
                        </div>
                        <div class="form-group col-sm-6 col-md-4">
                            <a href="javascript:void(0)" class="btn btn-white" v-show="created_at != ''" @click="clearFilter()">&times; Clear date filter</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="header-title">Add Questions</h4>
                            <p class="text-muted">Select Type and Subject to load available Questions.</p>
                            @if($errors->has('questions'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('questions') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <table class="table table-responsive table-hover model-list">
                        <thead>
                        <tr>
                            <th width="50">Add</th>
                            <th>Question</th>
                            <th></th>
                            <th>Topic</th>
                            <th>Created</th>
                            <th>Tags</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="question in questions">
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="questions[]" v-bind:value="question.id" :checked="questionSelected(question)" @click="selectQuestion(question)">
                                        <label></label>
                                    </div>
                                </td>
                                <td>
                                    <div v-if="question.title">@{{ question.title }}</div>
                                    <template v-if="question.type.key == 7">
                                        <lightbox :thumbnail="question.other_photo" :images="[question.other_photo]"></lightbox>
                                    </template>
                                    <template v-else>
                                        <img v-if="question.photo" :src="question.photo" class="img-responsive thumbnail">
                                    </template>
                                </td>
                                <td><span :class="['icon', 'icon-picture', question.answers_have_images? 'text-success' : 'text-muted']"></span></td>
                                <td>@{{ question.topic.name }}</td>
                                <td>@{{ question.date_created }}</td>
                                <td>
                                    <span class="label label-primary m-r-5" v-for="tag in question.tags">@{{ tag.name }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('quizzes.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
