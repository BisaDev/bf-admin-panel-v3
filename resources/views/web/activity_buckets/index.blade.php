@section('page_title', 'Activity Buckets')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Activity Buckets',
        'link' => [ 'label' => 'Create Activity Bucket', 'url' => route('activity_buckets.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Activity Buckets', 'url' => route('activity_buckets.index')],
        ],
        'currentSection' => 'All Activity Buckets',
    ])
@endsection

@section('content')
    <div id="index-academic-content" data-model="quiz" data-search="{{ $search or '' }}" class="row">
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
                        <form id="filter-form" class="form-inline" action="{{ route('activity_buckets.search') }}" method="POST">
                            {{ csrf_field() }}

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
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if(!is_null($filters['grade_level']) || !is_null($filters['subject']))
                                <a href="{{ route('activity_buckets.index') }}" class="btn btn-white">&times; Clear filters</a>
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

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th>Activity Bucket <a href="{{ route('activity_buckets.index').'?sort_column=title&sort_value='.$sort_columns['title'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th>Grade Level <a href="{{ route('activity_buckets.index').'?sort_column=grade_level&sort_value='.$sort_columns['grade_level'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th>Subject <a href="{{ route('activity_buckets.index').'?sort_column=subject&sort_value='.$sort_columns['subject'] }}{{ ($filter_string)? '&'.$filter_string : '' }}" class="fa fa-sort"></a></th>
                        <th width="90" class="text-center">Reorder</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><a href="{{ route('activity_buckets.show', $item->id) }}">{{ $item->title }}</a></td>
                            <td>{{ $item->subject->grade_level->name }}</td>
                            <td>{{ $item->subject->name }}</td>
                            <td class="text-center"><a href="{{ route('activity_buckets.reorder_quizzes', $item->id) }}" class="glyphicon glyphicon-sort"></a></td>
                            <td class="text-center"><a href="{{ route('activity_buckets.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('activity_buckets.destroy', $item->id) }}" method="POST" style="display: none;">
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
                        {{ $list->appends(['search' => $search])->links() }}
                        @else
                        {{ $list->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection