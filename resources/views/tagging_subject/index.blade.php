@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Tagging Subjects',
        'link' => [ 'label' => 'Create Subject', 'url' => route('taggingsubjects.create')],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Tagging Tool Subjects',
    ])
@endsection

@section('content')
    <div id="index-container" class="row">
        <div class="col-sm-12" id="app">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <thead>
                        <th>Subject</th>
                        <th>Topics</th>
                        <th width="90" class="text-center"></th>
                        <th width="90" class="text-center"></th>
                    </thead>
                    <tbody>
                    @foreach( $subjects as $subject )
                        <tr>
                            <th>
                                <a href={{route('taggingsubjects.show' , $subject->id)}}>
                                    {{ $subject->name }}
                                </a>
                            </th>
                            <td>{{ $subject->topics->count() }}</td>
                            <td class="text-center">View</td>
                            <td class="text-center">Edit</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection