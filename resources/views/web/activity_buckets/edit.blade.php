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

    <div class="row create-container" id="create-activity-bucket" data-quizzes="{{ json_encode($item->quizzes) }}">
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
                    </div>

                    <div class="row"><div class="col-sm-12"><hr/></div></div>

                    <div class="row">
                        <div class="form-group col-sm-6 {{ $errors->has('grade_level')? 'has-error' : '' }}">
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
                        <div class="form-group col-sm-6 {{ $errors->has('subject')? 'has-error' : '' }}">
                            <label class="control-label" for="subject">Subject:</label>
                            <select id="subject" name="subject" class="form-control" data-selected="{{ old('subject', $item->subject->id) }}" v-model="subject" @change="loadQuizzes('{{ route('quizzes.for_activity_bucket') }}', $event)">
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

                    <table class="table table-responsive table-hover">
                        <thead>
                        <tr>
                            <th width="90">Add</th>
                            <th>Quiz</th>
                            <th>Description</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="quiz in quizzes">
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="quizzes[]" :value="quiz.id"  :checked="quizSelected(quiz)">
                                        <label></label>
                                    </div>
                                </td>
                                <td>@{{ quiz.title }}</td>
                                <td>@{{ quiz.description }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('activity_buckets.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Edit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
