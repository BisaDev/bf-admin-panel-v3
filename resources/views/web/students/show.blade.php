@section('page_title', $item->full_name)
@extends('layouts.master')

@section('breadcrumbs')
    @include('partials.breadcrumbs', [
        'pageTitle' => $item->full_name,
        'link' => [ 'label' => 'Create Family Member', 'url' => route('family_members.create', $item->id)],
        'breadcrumbs' => [
            [ 'label' => 'Brightfox', 'url' =>  route('dashboard')],
            [ 'label' => 'Students', 'url' => route('students.index')],
        ],
        'currentSection' => $item->full_name,
    ])
@endsection

@section('content')
    <div id="show-student" class="row" data-model="family_member" data-search="{{ $search or '' }}"
         data-notes="{{ $item->notes->toJson() }}">
        <div class="col-md-8 col-md-offset-2">
            <div class="card-box">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-xs-4 col-xs-offset-4">
                                <img src="{{ $item->photo }}" class="img-responsive img-circle" alt="profile-image"
                                     data-pin-nopin="true">
                            </div>
                            <div class="col-xs-12 text-center">
                                <h2>{{ $item->full_name }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Birthdate:</label>
                        <p>{{ ($item->birthdate)? $item->birthdate->format('m/d/Y') : '' }}</p>
                    </div>

                    <div class="col-xs-6">
                        <label>Identify as:</label>
                        <p>{{ $item->gender->name }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>School Year:</label>
                        <p>{{ $item->school_year }}</p>
                    </div>

                    <div class="col-xs-6">
                        <label>Current School:</label>
                        <p>{{ $item->current_school }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Teacher:</label>
                        <p>{{ $item->teacher }}</p>
                    </div>
                    <div class="col-xs-6">
                        <label>Cohort:</label>
                        <p>{{ $item->cohort_tag }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Former School:</label>
                        <p>{{ $item->former_school }}</p>
                    </div>
                    <div class="col-xs-6">
                        <label>Location:</label>
                        <p>{{ (!is_null($item->location))? $item->location->name : 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Email:</label>
                        <p>{{ (!is_null($item->user))?((!is_null($item->user->email))? $item->user->email: 'N/A'): 'N/A' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <label>Phone:</label>
                        <p>{{ (!is_null($item->user))?((!is_null($item->user->user_detail->phone))? $item->user->user_detail->phone: 'N/A'): 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Mobile Phone:</label>
                        <p>{{ (!is_null($item->user))?((!is_null($item->user->user_detail->mobile_phone))? $item->user->user_detail->mobile_phone: 'N/A'): 'N/A' }}</p>
                    </div>
                    <div class="col-xs-6">
                        <label>Secondary Email:</label>
                        <p>{{ (!is_null($item->user))?((!is_null($item->user->user_detail->secondary_email))? $item->user->user_detail->secondary_email: 'N/A'): 'N/A' }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <hr/>
                    </div>
                </div>

                <form action="{{ route('students.save_notes', $item->id) }}" method="POST">
                    {{ csrf_field() }}

                    <div class="row">
                        @include('partials.add-notes')
                    </div>

                    <div class="row">
                        <div class="form-group col-md-12 text-right">
                            <button type="submit" class="btn btn-md btn-primary">Save notes</button>
                        </div>
                    </div>
                </form>

                <div class="row"> <div class="col-sm-12"> <hr/> </div> </div>

                <div class="row">
                    <div class="col-xs-6">
                        <h3>Attended Meetups</h3>
                    </div>
                    <div class="col-xs-6">
                        <div class="text-right results-title">
                            <h3>Progress report</h3>
                            <a href="{{ route('students.progress', $item->id) }}" class="icon icon-printer"></a>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <table class="table table-responsive table-hover model-list">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Activity</th>
                                <th>Hour</th>
                                <th width="90" class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($item->meetups as $meetup)
                                <tr>
                                    <td>{{ $meetup->start_time->format('m/d/Y') }}</td>
                                    <td>{{ $meetup->activity_bucket->title }}</td>
                                    <td>{{ $meetup->start_time->format('g:i a') }}</td>
                                    <td><a href="{{ route('meetups.student_detail', [$meetup->id, $item->id]) }}"
                                           class="btn btn-default">Details</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row m-t-20">
                    <div class="col-md-12 text-right">
                        <a href="{{ route('students.index') }}" class="btn btn-md btn-info">Back</a>
                        <a href="{{ route('students.edit', $item->id) }}" class="btn btn-md btn-primary">Edit</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            @if(Session::has('msg'))
                <div class="alert alert-{{ Session::get('msg.type') }} alert dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    {{ Session::get('msg.text') }}
                </div>
            @endif
            <div class="card-box">
                <div class="row">
                    <div class="col-md-offset-6 col-md-6 m-t-10">
                        <form class="form-inline" action="{{ route('students.show.search', $item->id) }}" method="POST">
                            {{ csrf_field() }}

                            <span class="form-control input-clear {{ isset($search)? 'active' : '' }}">
                                <input type="text" name="search" placeholder="Search" v-model="search">
                                <span @click="removeSearch('{{ route('students.show', $item->id) }}')" v-show="search">&times;</span>
                            </span>
                        </form>
                    </div>
                </div>

                <table class="table table-responsive table-hover model-list">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Family Member</th>
                        <th>Relationship</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Can Pickup</th>
                        <th>Active</th>
                        <th width="90" class="text-center">Edit</th>
                        <th width="90" class="text-center">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($item->family_members as $family_member)
                        <tr>
                            <td><img class="img-circle img-responsive" src="{{ $family_member->photo }}"/></td>
                            <td>
                                <a href="{{ route('family_members.show', $family_member->id) }}">{{ $family_member->full_name }}</a>
                            </td>
                            <td>{{ ucwords($family_member->type->name) }}</td>
                            <td>{{ $family_member->email }}</td>
                            <td>{{ $family_member->mobile_phone }}</td>
                            <td>
                                <input type="checkbox"
                                       {{ $family_member->can_pickup? 'checked' : '' }} data-plugin="switchery"
                                       data-color="#FC7044" data-size="small"
                                       @change="toggleActive('{{ route('family_members.toggle_pickup', $family_member->id) }}', $event)"/>
                            </td>
                            <td>
                                <input type="checkbox"
                                       {{ $family_member->active? 'checked' : '' }} data-plugin="switchery"
                                       data-color="#FC7044" data-size="small"
                                       @change="toggleActive('{{ route('family_members.toggle_active', $family_member->id) }}', $event)"/>
                            </td>
                            <td class="text-center"><a href="{{ route('family_members.edit', $family_member->id) }}"
                                                       class="icon icon-pencil"></a></td>
                            <td class="text-center">
                                <a href="" @click="confirmDelete({{ $family_member->id }}, 0, $event)"
                                   class="icon icon-trash"></a>
                                <form id="delete-form-{{ $family_member->id }}"
                                      action="{{ route('family_members.destroy', $family_member->id) }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="row">
                    <div class="col-sm-12 text-right">
                        {{ $item->family_members->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
