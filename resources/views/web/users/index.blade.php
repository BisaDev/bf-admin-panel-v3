@section('page_title', 'Employees')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Employees',
        'link' => [ 'label' => 'Create Employee', 'url' => route('employees.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Employees', 'url' => route('employees.index')],
        ],
        'currentSection' => 'All Employees',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="user" data-search="{{ $search or '' }}" class="row">
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
                        <form class="form-inline" action="{{ route('employees.search') }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('employees.index') }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><img class="img-circle img-responsive" src="{{ $item->user_detail->photo }}" /></td>
                            <td><a href="{{ route('employees.show', $item->id) }}">{{ $item->full_name }}</a></td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->user_detail->mobile_phone }}</td>
                            <td>{{ ucfirst($item->roles->first()->name) }}</td>
                            <td class="text-center"><a href="{{ route('employees.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('employees.destroy', $item->id) }}" method="POST" style="display: none;">
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
                        {{ $list->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection