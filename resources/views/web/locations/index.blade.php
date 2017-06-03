@section('page_title', 'Locations')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Locations',
        'link' => [ 'label' => 'Create Location', 'url' => route('locations.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Facilities', 'url' => route('locations.index')],
        ],
        'currentSection' => 'All Locations',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="location" data-model-child="room" data-search="{{ $search or '' }}" class="row">
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
                        <form class="form-inline" action="{{ route('locations.search') }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('locations.index') }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                    <tr>
                        <th>Location</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Phone Number</th>
                        <th>Contact Email</th>
                        <th>Active</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><a href="{{ route('locations.show', $item->id) }}">{{ $item->name }}</a></td>
                            <td>{{ $item->city }}</a></td>
                            <td>{{ $item->state }}</a></td>
                            <td>{{ $item->phone }}</a></td>
                            <td>{{ $item->email }}</a></td>
                            <td>
                                <input type="checkbox" {{ $item->active? 'checked' : '' }} data-plugin="switchery" data-color="#FC7044" data-size="small" @change="toggleActive('{{ route('locations.toggle_active', $item->id) }}', $event)"/>
                            </td>
                            <td class="text-center"><a href="{{ route('locations.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, {{ $item->rooms->count() }}, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('locations.destroy', $item->id) }}" method="POST" style="display: none;">
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