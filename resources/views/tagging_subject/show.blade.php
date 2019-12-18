@section('page_title', 'Subject details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'dude',
        'link' => [ 'label' => 'Create Topic', 'url' => '/dude'],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => '/dude'],
            [ 'label' => 'dude', 'url' => '/dude'],
        ],
        'currentSection' => "Dude",
    ])
@endsection

@section('content')
    <div id="index-container" data-model="topic" class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-6">
                        <h3>Subject: dude</h3>
                    </div>
                </div>

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Topic</th>
                        <th width="90" class="text-center">Edit</th>
                    </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>topic</td>
                            <td>topic name</td>
                            <td class="text-center"><a href="/topic" class="icon icon-pencil"></a></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
