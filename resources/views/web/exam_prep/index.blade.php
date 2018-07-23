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
    <div class="row">
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
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($examList as $exam)
                                    <tr>
                                        <td><a href="{{ route('exams.show', $exam->id)}}"> {{ $exam->test_id }} </a></td>
                                        <td>{{ $exam->type }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
