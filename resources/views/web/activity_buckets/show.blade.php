@section('page_title', 'Activity bucket details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Activity bucket details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Activity Buckets', 'url' => route('activity_buckets.index')]
        ],
        'currentSection' => 'Activity bucket details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-activity-bucket" data-activity-bucket-id="{{ $item->id }}" data-quizzes="{{ json_encode($item->quizzes) }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->title }}</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label class="control-label">Grade Level:</label>
                        <p>{{ $item->subject->grade_level->name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label">Subject:</label>
                        <p>{{ $item->subject->name }}</p>
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>

                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0">Questions</h4>
                        <p class="text-muted">Drag questions to re-arrange.</p>
                    </div>
                </div>

                <draggable class="list-group" v-model="quizzes_selected" :options="dragOptions" @end="onDragEnd('{{ route('activity_buckets.save_quiz_order') }}', $event)">
                    <div v-for="quiz in quizzes_selected" class="list-group-item">
                        <div class="row">
                            <div class="col-xs-2"><span class="drag_handle">&#9776;</span></div>
                            <div class="col-xs-10">
                                <h4 class="list-group-item-heading">@{{ quiz.title }}</h4>
                                <p class="list-group-item-text">@{{ quiz.description }}</p>
                            </div>
                        </div>
                    </div>
                </draggable>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('activity_buckets.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('activity_buckets.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
