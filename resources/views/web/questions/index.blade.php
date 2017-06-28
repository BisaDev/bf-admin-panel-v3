@section('page_title', 'Questions')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Questions',
        'link' => [ 'label' => 'Create Question', 'url' => route('questions.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Questions', 'url' => route('questions.index')],
        ],
        'currentSection' => 'All Questions',
    ])
@endsection

@section('content')
    <div id="index-academic-content" data-model="question" data-search="{{ $search or '' }}" class="row">
        <div class="col-sm-12">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12 m-t-10 m-b-10">
                        <form id="filter-form" class="form-inline" action="{{ route('questions.search') }}" method="POST">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <select id="type" name="type" class="form-control" v-model="type">
                                    <option value="">Select Type</option>
                                    @foreach($types as $key => $type)
                                    <option value="{{ $key }}" {{ (!is_null($filters['type']) && (int)$filters['type'] === $key)? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="grade_level" name="grade_level" class="form-control" data-selected="{{ $filters['grade_level'] }}" @change="getSubjectsFromGradeLevel('{{ route('subjects.by_grade') }}', $event)">
                                    <option value="">Select Grade Level</option>
                                    @foreach($grade_levels as $grade_level)
                                    <option value="{{ $grade_level->id }}">{{ ucfirst($grade_level->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="subject" name="subject" class="form-control" data-selected="{{ $filters['subject'] }}" @change="getTopicsFromSubject('{{ route('topics.by_subject') }}', $event)">
                                    <option value="">Select subject</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="topic" name="topic" class="form-control" data-selected="{{ $filters['topic'] }}">
                                    <option value="">Select topic</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if(!is_null($filters['type']) || !is_null($filters['grade_level']) || !is_null($filters['subject']) || !is_null($filters['topic']))
                                <a href="{{ route('questions.index') }}" class="btn btn-white">&times; Clear filters</a>
                                @endif
                            </div>
                            
                            <div class="form-group col-md-4 pull-right">
                                <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                    <input type="text" id="search" name="search" placeholder="Search" v-model="search" >
                                    <span @click="removeSearch()" v-show="search">&times;</span>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                    <tr>
                        <th>Question</th>
                        <th></th>
                        <th>Grade Level</th>
                        <th>Subject</th>
                        <th>Topic</th>
                        <th>Type</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><a href="{{ route('questions.show', $item->id) }}">{{ $item->title }}@if($item->photo)<img src="{{ $item->photo }}" class="img-responsive">@endif</a></td>
                            <td><span class="icon icon-picture {{ ($item->answers_have_images)? 'text-success' : 'text-muted' }}"></span></td>
                            <td>{{ $item->topic->subject->grade_level->name }}</td>
                            <td>{{ $item->topic->subject->name }}</td>
                            <td>{{ $item->topic->name }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td class="text-center"><a href="{{ route('questions.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('questions.destroy', $item->id) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        @if(isset($search))
                        {{ $list->appends(array_merge(['search' => $search], $filters))->links() }}
                        @else
                        {{ $list->appends($filters)->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection