@section('page_title', 'Subject details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Subject',
        'link' => [ 'label' => 'Create Topic', 'url' => '/Subject'],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Subject', 'url' => '/Subject'],
        ],
        'currentSection' => $item,
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
                    @foreach($item->topics as $topic)
                        <tr>
                            <td>topic</td>
                            <td>{{$topic->id}}</td>
                            <td class="text-center"><a href="/topic" class="icon icon-pencil"></a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
