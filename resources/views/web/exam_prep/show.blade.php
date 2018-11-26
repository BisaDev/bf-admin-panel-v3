@extends('layouts.master')

@section('page_title', 'Exam details')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => $exam->test_id,
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Exams', 'url' =>  route('exams.index')],
        ],
        'currentSection' => $exam->test_id,
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
                            </tr>
                            </thead>

                            <tbody>
                            @if($exam->IsMiniExam)
                                <tr>
                                    <td>{{ $exam->test_id }}</td>
                                    <td>{{ $exam->type }}</td>
                                    <td><a href="{{ route('exams.section.show', [$exam->id, 1])}}"> Section 1 </a></td>
                                    <td class="text-center"><a href="{{ route('exams.section.edit', [$exam->id, 1])}}" class="icon icon-pencil"></a></td>
                                </tr>
                            @else
                                @foreach($exam->sectionsMetadata as $section)
                                    <tr>
                                        <td>{{ $exam->test_id }}</td>
                                        <td>{{ $exam->type }}</td>
                                        <td><a href="{{ route('exams.section.show', [$exam->id, $section->section_number])}}">{{ $section->section_name }}</a></td>
                                        <td class="text-center"><a href="{{ route('exams.section.edit', [$exam->id, $section->section_number])}}" class="icon icon-pencil"></a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
