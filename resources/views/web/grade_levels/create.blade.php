@section('page_title', 'Create grade level')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Create grade level',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Academic Content', 'url' => route('grade_levels.index')],
        ],
        'currentSection' => 'Create grade level',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-quiz">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <form action="{{ route('grade_levels.store') }}" method="POST">
                        {{ csrf_field() }}

                        <div class="col-sm-12">
                            <h4 class="header-title">Let's create a new Grade Level</h4>
                            <p class="m-b-30">A Grade Level is an Academic Content Level</p>
                        </div>

                        <div class="form-group col-md-12 {{ $errors->has('name')? 'has-error' : '' }}">
                            <label class="control-label" for="name">Grade Level Name:</label>
                            <input type="text" name="name" class="form-control">
                            @if($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div id="add-children">
                            <div class="form-group col-md-12">
                                <button type="button" class="btn btn-sm btn-default" @click="addChildren" >Add subject<span class="m-l-5"><i class="fa fa-plus"></i></span></button>
                            </div>

                            <div class="form-group col-md-12" v-for="(subject, index) in children">
                                <span class="form-control input-clear active">
                                    <input type="text" name="subjects[]" placeholder="Subject name" v-model="subject.name">
                                    <span @click="removeChildren(index)">&times;</span>
                                </span>
                            </div>
                        </div>

                        <div class="form-group col-md-12 text-right">
                            <a href="{{ url()->previous() }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Create</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
