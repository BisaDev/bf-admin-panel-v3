@section('page_title', 'Quiz details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Quiz details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Quizzes', 'url' => route('quizzes.index')]
        ],
        'currentSection' => 'Quiz details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz" data-quiz-id="{{ $item->id }}" data-questions="{{ json_encode($item->questions) }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->title }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <label>Description</label>
                        <p>{{ $item->description }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label>Type</label>
                        <p>{{ $item->type->name }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ ($item->subject)? $item->subject->grade_level->name : '' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ ($item->subject)? $item->subject->name : '' }}</p>
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Questions</h4>
                        <p class="text-muted">Drag questions to re-arrange.</p>
                    </div>
                </div>

                <draggable class="list-group" v-model="questions_selected" :options="dragOptions" @end="onDragEnd('{{ route('quizzes.save_question_order') }}', $event)">
                    <div v-for="question in questions_selected" class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2"><span class="drag_handle">&#9776;</span></div>
                            <div class="col-xs-10">
                                <h4 class="list-group-item-heading" v-if="question.title">@{{ question.title }}</h4>
                                <img v-bind:src="question.photo" class="img-responsive thumbnail" v-if="question.photo">
                            </div>
                        </div>
                    </div>
                </draggable>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('quizzes.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('quizzes.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
