@section('page_title', 'Students')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Students',
        'link' => [ 'label' => 'Create Student', 'url' => route('students.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')],
        ],
        'currentSection' => 'All Students',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="user" data-model-child="family member" data-search="{{ $search or '' }}" class="row">
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
                        <form class="form-inline" action="{{ route('students.search') }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('students.index') }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Student</th>
                        <th>School Year</th>
                        <th>Location</th>
                        <th>Cohort</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><img class="img-circle img-responsive" src="{!! $item->photo !!}" /></td>
                            <td><a href="{{ route('students.show', $item->id) }}">{{ $item->full_name }}</a></td>
                            <td>{{ $item->school_year }}</td>
                            <td>{{ (!is_null($item->location))? $item->location->name : 'N/A' }}</td>
                            <td> {{ (!is_null($item->cohort_tag ))? $item->cohort_tag : 'N/A' }}</td>
                            <td class="text-center"><a href="{{ route('students.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, {{ $item->family_members->count() }}, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('students.destroy', $item->id) }}" method="POST" style="display: none;">
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