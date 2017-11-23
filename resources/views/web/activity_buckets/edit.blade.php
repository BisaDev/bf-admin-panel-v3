@section('page_title', 'Edit activity bucket')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Edit activity bucket',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Activity Buckets', 'url' => route('activity_buckets.index')]
        ],
        'currentSection' => 'Edit activity bucket',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-activity-bucket" data-quizzes="{{ $item->quizzes->toJson() }}">
        <form action="{{ route('activity_buckets.update', $item->id) }}" method="POST">
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
                        <div class="form-group col-xs-12 {{ $errors->has('presentation_file')? 'has-error' : '' }}">
                            <label class="control-label" for="presentation_file">Presentation File Link:</label>
                            <input type="text" name="presentation_file" class="form-control" value="{{ old('presentation_file', $item->presentation_file) }}">
                            @if($errors->has('presentation_file'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('presentation_file') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('grade_level')? 'has-error' : '' }}">
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
                        <div class="form-group col-sm-6 {{ $errors->has('subject')? 'has-error' : '' }}">
                            <label class="control-label" for="subject">Subject:</label>
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', ($item->subject)? $item->subject->id : '') }}" v-model="subject" @change="loadQuizzes('{{ route('quizzes.for_activity_bucket') }}', $event)">
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
                            <h4 class="header-title">Add Quizzes</h4>
                            <p class="text-muted">Select Subject to load available Quizzes.</p>
                            @if($errors->has('quizzes'))
                                <div class="alert alert-danger" role="alert">
                                    {{ $errors->first('quizzes') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <table class="table table-responsive table-hover model-list">
                        <thead>
                        <tr>
                            <th width="50">Add</th>
                            <th>Quiz</th>
                            <th>Type</th>
                            <th># of questions</th>
                            <th>Tags</th>
                            <th width="150">Minigames</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(quiz, index) in quizzes">
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" :name="'quizzes['+quiz.id+']'" :value="quiz.id"  :checked="quizSelected(quiz)">
                                        <label></label>
                                    </div>
                                </td>
                                <td>@{{ quiz.title }}</td>
                                <td>@{{ quiz.type.name }}</td>
                                <td>@{{ quiz.number_of_questions }}</td>
                                <td>
                                    <span class="label label-primary m-r-5" v-for="tag in quiz.tags">@{{ tag.name }}</span>
                                </td>
                                <td>
                                    <select :name="'quizzes_minigames['+quiz.id+'][minigame_id]'" class="form-control" v-show="quizAllowsMinigames(quiz.type.key)">
                                        <option value="">Select Minigame</option>
                                        @foreach($minigames as $minigame)
                                        <option value="{{ $minigame->id }}" :selected="minigameSelected(quiz, {{ $minigame->id }})">{{ $minigame->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('activity_buckets.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
