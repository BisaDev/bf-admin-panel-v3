@extends('layouts.student_master')

@section('page_title', 'Analytics')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Analytics',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('student_dashboard')],
        ],
        'currentSection' => 'Analytics',
    ])
@endsection

@section('content')

    <div id="app" class="container">
        <div class="row">
            <div class="card-box col-md-12">
                <table class="table table-responsive table-hover model-list text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Topic <a
                                    href="{{ route('answer_sheet.analytics'). '?sort_column=topic&sort_value='.$sortValue }}"
                                    class="fa fa-sort"></a></th>
                        <th class="text-center">Overall <a
                                    href="{{ route('answer_sheet.analytics'). '?sort_column=overall&sort_value='.$sortValue }}"
                                    class="fa fa-sort"></a></th>
                        @foreach($dates as $date)
                            <th class="text-center">{{$date}} <a
                                        href="{{ route('answer_sheet.analytics'). '?sort_column=' . $date . '&sort_value='.$sortValue }}"
                                        class="fa fa-sort"></a></th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sortedBy === 'overall' ? $overall : $questionsByMonth as $key => $question)
                        <tr>
                            <td> {{$key}} </td>
                            <td> {{ $overall[$key]['correct'] }} / {{ $overall[$key]['total'] }}
                                ({{$overall[$key]['avg']}}%)
                            </td>
                            @foreach($dates as $date)
                                @if(isset($questionsByMonth[$key][$date]))
                                    <td class="text-center">{{$questionsByMonth[$key][$date]['correct']}}
                                        / {{ $questionsByMonth[$key][$date]['total'] }}
                                        ({{$questionsByMonth[$key][$date]['avg']}}%)
                                    </td>
                                @else
                                    <th class="text-center"> -</th>
                                @endif
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
