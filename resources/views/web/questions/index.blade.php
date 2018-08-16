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
                <form id="filter-form" class="form-inline" action="{{ route('questions.search') }}" method="POST">
                    <div class="row">
                        <div class="col-xs-12 m-t-10 m-b-10">
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
                                <input type="text" name="created_at" class="form-control datepicker-general" placeholder="Created Date" value="{{ $filters['created_at'] or '' }}" >
                            </div>

                            <div class="form-group col-md-4 pull-right">
                                <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                    <input type="text" id="search" name="search" placeholder="Search" v-model="search" >
                                    <span @click="removeSearch()" v-show="search">&times;</span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 m-b-10">
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
                                @if(!is_null($filters['type']) || !is_null($filters['grade_level']) || !is_null($filters['subject']) || !is_null($filters['topic']) || !is_null($filters['created_at']) )
                                    <a href="{{ route('questions.index') }}" class="btn btn-white">&times; Clear filters</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th nowrap>Question <a href="{{ route('questions.index').'?sort_column=title&sort_value='.$sort_columns['title'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th></th>
                        <th nowrap>Grade Level <a href="{{ route('questions.index').'?sort_column=grade_level&sort_value='.$sort_columns['grade_level'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th nowrap>Subject <a href="{{ route('questions.index').'?sort_column=subject&sort_value='.$sort_columns['subject'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th nowrap>Topic <a href="{{ route('questions.index').'?sort_column=topic&sort_value='.$sort_columns['topic'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th nowrap>Type <a href="{{ route('questions.index').'?sort_column=type&sort_value='.$sort_columns['type'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th nowrap>User <a href="{{ route('questions.index').'?sort_column=user&sort_value='.$sort_columns['user'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th nowrap>Created <a href="{{ route('questions.index').'?sort_column=created_at&sort_value='.$sort_columns['created_at'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            @if($item->type->key == 7)
                                <td><a href="{{ route('questions.show', $item->id) }}">{{ $item->title }}</a><lightbox thumbnail="{{ $item->other_photo}}" :images="['{{ $item->other_photo}}']"></lightbox> </td>
                            @else
                                <td><a href="{{ route('questions.show', $item->id) }}">{{ $item->title }}</a><lightbox thumbnail="{{ $item->photo }}" :images="['{{ $item->photo }}']"></lightbox> </td>
                            @endif
                            <td><img class="icon-images" src="{{ ($item->answers_have_images)? asset('images/icon-have-image.png') : asset('images/icon-no-image.png') }}" ></td>
                            <td>{{ ($item->topic)? $item->topic->subject->grade_level->name : '' }}</td>
                            <td>{{ ($item->topic)? $item->topic->subject->name : '' }}</td>
                            <td>{{ ($item->topic)? $item->topic->name : '' }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td>{{ ($item->user)? $item->user->full_name : '' }}</td>
                            <td>{{ $item->date_created }}</td>
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
