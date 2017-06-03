@section('page_title', 'Subject details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Subject details',
        'link' => [ 'label' => 'Create Topic', 'url' => route('topics.create', $item->id)],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
            [ 'label' => 'Grade level details', 'url' => route('grade_levels.show', $item->grade_level->id)],
        ],
        'currentSection' => 'Subject details',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="topic" data-search="{{ $search or '' }}" class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Grade Level: {{ $item->grade_level->name }}</h3>
                        <h4 class="m-b-20">{{ $item->name }}</h4>
                    </div>
                    <div class="col-md-6 m-t-10">
                        <form class="form-inline" action="{{ route('subjects.show.search', $item->id) }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('subjects.show', $item->id) }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>
                
                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Topic</th>
                            <th width="90" class="text-center">Edit</th>
                            <th width="90" class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($item->topics as $topic)
                        <tr>
                            <td>{{ $topic->name }}</td>
                            <td class="text-center"><a href="{{ route('topics.edit', $topic->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $topic->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $topic->id }}" action="{{ route('topics.destroy', $topic->id) }}" method="POST" style="display: none;">
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
                        {{ $item->topics->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
