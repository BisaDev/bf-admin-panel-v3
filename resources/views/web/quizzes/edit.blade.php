@section('page_title', 'Edit quiz')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit quiz',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('quizzes.index')]
        ],
        'currentSection' => 'Edit quiz',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz" data-questions="{{ json_encode($item->questions) }}">
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
                            <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ old('grade_level', $item->subject->grade_level->id) }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
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
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', $item->subject->id) }}" v-model="subject" @change="loadQuestions('{{ route('questions.for_quiz') }}', $event)">
                            </select>
                            @if($errors->has('subject'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('subject') }}</strong>
                                </span>
                            @endif
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

                    <table class="table table-responsive table-hover">
                        <thead>
                        <tr>
                            <th width="90" class="text-center">Add</th>
                            <th>Question</th>
                            <th>Topic</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="question in questions">
                                <td>
                                    <div class="checkbox checkbox-primary text-center">
                                        <input type="checkbox" name="questions[]" :value="question.id" :checked="questionSelected(question)">
                                        <label></label>
                                    </div>
                                </td>
                                <td>
                                    <div v-if="question.title">@{{ question.title }}</div>
                                    <img v-if="question.photo" :src="question.photo">
                                </td>
                                <td>@{{ question.topic.name }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('quizzes.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
