@section('page_title', 'Grade levels')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Grade Levels',
        'link' => [ 'label' => 'Create Grade Level', 'url' => route('grade_levels.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
        ],
        'currentSection' => 'All Grade Levels',
    ])
@endsection

@section('content')
    <div id="index-container" data-model="grade level" data-model-child="subject" class="row">
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
                        <th>Grade Level</th>
                        <th>Subjects</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $grade_level)
                        <tr>
                            <td><a href="{{ route('grade_levels.show', $grade_level->id) }}">{{ $grade_level->name }}</a></td>
                            <td>{{ $grade_level->subjects->count() }}</td>
                            <td class="text-center"><a href="{{ route('grade_levels.edit', $grade_level->id) }}" class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $grade_level->id }}, {{ $grade_level->subjects->count() }}, $event)" class="icon icon-trash"></a>
                                <form id="delete-form-{{ $grade_level->id }}" action="{{ route('grade_levels.destroy', $grade_level->id) }}" method="POST" style="display: none;">
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