@section('page_title', 'Tagging Tool')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Grade Levels',
        'link' => [ 'label' => 'Create Topic', 'url' => '/taggingtopics/create'],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')]
        ],
        'currentSection' => 'Tagging Tool Topics',
    ])
@endsection

@section('content')
    <div id="index-container" class="row">
        <div class="col-sm-12" id="app">
            <div class="card-box">
                <table class="table table-responsive table-hover model-list">
                    <tbody>
                    @foreach( $topics as $topic )
                        <tr>
                            <th>{{ $topic->name }}</th>
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