@section('page_title', 'Meetup attendance')
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => 'Meetup attendance',
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Meetups', 'url' => route('meetups.index')]
        ],
        'currentSection' => 'Meetup attendance',
    ])
@endsection

@section('content')

    <div class="row create-container" id="create-meetup" data-students="{{ $students->toJson() }}" data-students-selected="{{ $item->students->toJson() }}">
        <form action="{{ route('meetups.attendance.store', $item->id) }}" method="POST">
            {{ csrf_field() }}

            <div class="col-md-10 col-md-offset-1">
                <div class="card-box">

                    <table class="table table-responsive table-hover">
                        <thead>
                        <tr>
                            <th width="50">Add</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="student in students">
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input type="checkbox" name="students[]" v-bind:value="student.id" :checked="studentSelected(student)">
                                        <label></label>
                                    </div>
                                </td>
                                <td>@{{ student.name }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <a href="{{ route('meetups.index') }}" class="btn btn-md btn-info">Cancel</a>
                            <button type="submit" class="btn btn-md btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
