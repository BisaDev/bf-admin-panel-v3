@section('page_title', 'Minigame details')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Minigame details',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Minigames', 'url' => route('minigames.index')]
        ],
        'currentSection' => 'Minigame details',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-container">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="m-b-20">{{ $item->name }}</h3>
                    </div>
                    <div class="col-xs-12">
                        <img src="{{ $item->photo }}" class="img-responsive center-block m-b-15">
                    </div>
                </div>

                <div class="row"><div class="col-sm-12"><hr/></div></div>
                <div class="row">
                    <div class="col-sm-12">
                        <h4 class="header-title m-t-0 m-b-15">Notes</h4>
                    </div>
                </div>

                <div class="row">
                    @foreach($item->notes as $note)
                    <div class="col-sm-6">
                        <label>{{ $note->title }}</label>
                        <p>{{ $note->text }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="row">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('minigames.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('minigames.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
