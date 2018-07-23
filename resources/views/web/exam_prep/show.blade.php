@extends('layouts.master')

@section('page_title', 'Exam details')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => $item->test_id,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' =>  route('exams.index')],
        ],
        'currentSection' => $item->test_id,
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-responsive model-list">

                            <thead>
                            <tr>
                                <th>Exam</th>
                                <th>Exam Type</th>
                                <th>Section</th>
                                <th width="90" class="text-center">Edit</th>
                                <th width="90" class="text-center">Delete</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($sections as $sectionId => $section)
                                <tr>
                                    <td>{{ $item->test_id }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td> {{ $section }} </td>
                                    <td class="text-center"><a href="{{ route('exams.section.edit', [$item->id, $sectionId])}}" class="icon icon-pencil"></a></td>
                                    <td class="text-center">
                                        <a href="{{ route('exams.index')}}" class="icon icon-trash"></a>
                                    </td>
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
