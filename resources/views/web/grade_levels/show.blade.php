@section('page_title', 'Grade level details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Grade level details',
        'link' => [ 'label' => 'Create Subject', 'url' => route('subjects.create', $item->id)],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
        ],
        'currentSection' => 'Grade level details',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="subject" data-model-child="topic" data-search="{{ $search or '' }}" class="row">
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
                        <h3 class="m-b-20">Grade Level: {{ $item->name }}</h3>  
                    </div>
                    <div class="col-md-6 m-t-10">
                        <form class="form-inline" action="{{ route('grade_levels.show.search', $item->id) }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('grade_levels.show', $item->id) }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Topics</th>
                            <th width="90" class="text-center">Edit</th>
                            <th width="90" class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($item->subjects as $subject)
                        <tr>
                            <td><a href="{{ route('subjects.show', $subject->id) }}">{{ $subject->name }}</a></td>
                            <td>{{ $subject->topics->count() }}</td>
                            <td class="text-center"><a href="{{ route('subjects.edit', $subject->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $subject->id }}, {{ $subject->topics->count() }}, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $subject->id }}" action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
