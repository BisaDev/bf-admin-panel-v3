@section('page_title', 'Location details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Location details',
        'link' => [ 'label' => 'Create Room', 'url' => route('rooms.create', $item->id)],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Facilities', 'url' => route('locations.index')],
        ],
        'currentSection' => 'Location details',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="room" data-search="{{ $search or '' }}" class="row">
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
                        <h3 class="m-b-20">{{ $item->name }}</h3>  
                    </div>
                    <div class="col-md-6 m-t-10">
                        <form class="form-inline" action="{{ route('locations.show.search', $item->id) }}" method="POST">
                            {{ csrf_field() }}
                            
                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search" >
                                <span @click="removeSearch('{{ route('locations.show', $item->id) }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover">
                    <thead>
                        <tr>
                            <th>Room</th>
                            <th width="90" class="text-center">Edit</th>
                            <th width="90" class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($item->rooms as $room)
                        <tr>
                            <td>{{ $room->name }}</td>
                            <td class="text-center"><a href="{{ route('rooms.edit', $room->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $room->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $room->id }}" action="{{ route('rooms.destroy', $room->id) }}" method="POST" style="display: none;">
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
                        {{ $item->rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
