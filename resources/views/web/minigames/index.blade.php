@section('page_title', 'Minigames')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Minigames',
        'link' => [ 'label' => 'Create Minigame', 'url' => route('minigames.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Minigames', 'url' => route('minigames.index')],
        ],
        'currentSection' => 'All Minigames',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="minigame" class="row">
        <div class="col-sm-12">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">

                <table class="table table-responsive table-hover">
                    <thead>
                    <tr>
                        <th>Minigame</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td><a href="{{ route('minigames.show', $item->id) }}">{{ $item->name }}</a></td>
                            <td class="text-center"><a href="{{ route('minigames.edit', $item->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $item->id }}, 0, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $item->id }}" action="{{ route('minigames.destroy', $item->id) }}" method="POST" style="display: none;">
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