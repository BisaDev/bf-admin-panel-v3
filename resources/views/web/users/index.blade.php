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
    <div id="index-users" data-model="user" data-search="{{ $search or '' }}" class="row">
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
                        <form id="filter-form" class="form-inline" action="{{ route('employees.search') }}" method="POST">
                            {{ csrf_field() }}
                
                            <div class="form-group">
                                <select id="location" name="location" class="form-control" v-model="location">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id  }}" {{ (!is_null($filters['location']) && (int)$filters['location'] === $location->id)? 'selected' : '' }}>{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <select id="role" name="role" class="form-control" v-model="role">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name  }}" {{ (!is_null($filters['role']) && $filters['role'] === $role->name)? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                @if(!is_null($filters['location']) || !is_null($filters['role']))
                                    <a href="{{ route('employees.index') }}" class="btn btn-white">&times; Clear filters</a>
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
                        <th></th>
                        <th>Employee</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Location</th>
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
                            <td>{{ $item->user_detail->location->name }}</td>
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