@extends('layouts.master')

@section('page_title', 'Exams')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Exams',
        'link' => [ 'label' => 'Create Exam', 'url' => route('exams.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
        ],
        'currentSection' => 'Exams',
    ])
@endsection

@section('content')
    <div id="index-academic-content" class="row">
        <div class="col-md-12">

            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif

            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">

                        <table class="table table-responsive model-list">
                            <thead>
                                <tr>
                                    <th>Exam</th>
                                    <th>Exam Type</th>
                                    <th>Source</th>
                                    <th>Description</th>
                                    <th width="90" class="text-center">Delete</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($examList as $exam)
                                    <tr>
                                        <td><a href="{{ route('exams.show', $exam->id)}}"> {{ $exam->test_id }} </a></td>
                                        <td>{{ $exam->type }}</td>
                                        <td>{{ $exam->source }}</td>
                                        <td>{{ $exam->description }}</td>
                                        <td class="text-center">
                                            <a href="" @click="confirmDelete({{ $exam->id }}, 0, $event)" class="icon icon-trash"></a>
                                            <form id="delete-form-{{ $exam->id }}" action="{{ route('exams.destroy', $exam->id) }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                                {{ method_field('delete') }}
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            {{ $examList->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
