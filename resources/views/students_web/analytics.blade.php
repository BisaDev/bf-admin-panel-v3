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
                {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                <table class="table table-responsive table-hover model-list text-center">
                    <thead>
                    <tr>
                        <th class="text-center">Topic</th>
                        <th class="text-center">Overall</th>
                        @foreach($dates as $date)
                            <th class="text-center">{{$date}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($overall as $key => $question)
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
            {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>
@endsection
