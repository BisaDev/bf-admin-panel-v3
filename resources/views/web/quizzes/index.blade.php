@section('page_title', 'Quizzes')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Quizzes',
        'link' => [ 'label' => 'Create Quizz', 'url' => route('quizzes.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Quizzes', 'url' => route('quizzes.index')],
        ],
        'currentSection' => 'All Quizzes',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="quiz" data-search="{{ $search or '' }}" class="row">
        <div class="col-sm-12">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">
                <div class="row">
                    <div class="col-md-offset-8 col-md-4 m-t-10 m-b-10">
                        <form class="form-inline" action="{{ route('quizzes.search') }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('quizzes.index') }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                    <tr>
                        <th>Quizz</th>
                        <th>Grade Level</th>
                        <th>Subject</th>
                        <th>Type</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><a href="{{ route('quizzes.show', $item->id) }}">{{ $item->title }}</a></td>
                            <td>{{ $item->subject->grade_level->name }}</td>
                            <td>{{ $item->subject->name }}</td>
                            <td>{{ $item->type->name }}</td>
                            <td class="text-center"><a href="{{ route('quizzes.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('quizzes.destroy', $item->id) }}" method="POST" style="display: none;">
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