@extends('layouts.master')
@section('title')
Single User
@stop

@section('content')
    <div class="container">
        <div class="row">


            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">User</div>
                    <div class="card-body">

                        <a href="{{ url('/admin/users') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" title="Edit User"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        {!! Form::open([
                            'method' => 'DELETE',
                            'url' => ['/admin/users', $user->id],
                            'style' => 'display:inline'
                        ]) !!}

                        {!! Form::close() !!}
                        <br/>
                        <br/>
                        <div class="card mb-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="profile-view">
                                            <div class="profile-img-wrap">
                                                <div class="profile-img">
                                                    <a href="#"><img alt=""
                                                                     @if ($user->logo != null && file_exists(public_path('images/'.$user->logo)))
                                                                     src="{{asset('images/'.$user->logo.'')}}"
                                                                     @else
                                                                     src="{{asset('img/user.jpg')}}"
                                                            @endif
                                                        ></a>
                                                </div>
                                            </div>
                                            <div class="profile-basic">
                                                <div class="row">
                                                    <div class="col-md-5 mt-3">
                                                        <div class="profile-info-left">
                                                            <h3 class="user-name mb-0">{{ucfirst($user->name)}}</h3> <br>
                                                            <small class="text-muted">{{$user->role->label}}</small>
                                                            <div class="staff-id">Location : {{$user->location}}</div>
                                                            <div class="small doj text-muted">Registered on: {{ date_format(date_create($user->created_at), "jS F Y") }}</div>
                                                            @if ($user->status == 1)
                                                                {!! Form::open([
                                                                'method' => 'PUT',
                                                                'url' => ['/deactiveUser', $user->id],
                                                                'style' => 'display:inline'
                                                                ]) !!}
                                                                <div class="staff-msg"><button type="submit" class="btn btn-danger"    onclick="return confirm(&quot;Are you sure you want to deactive this user?&quot;)">Deactive User</button></div>
                                                                {!! Form::close() !!}
                                                            @else
                                                                {!! Form::open([
                                                                 'method' => 'PUT',
                                                                 'url' => ['/activateUser', $user->id],
                                                                 'style' => 'display:inline'
                                                                 ]) !!}
                                                                <div class="staff-msg"><button class="btn btn-custom" onclick="return confirm(&quot;Are you sure you want to activate this user?&quot;)">Activate User</button></div>
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-7 mt-3">
                                                        <ul class="personal-info">
                                                            <li>
                                                                <div class="title">Contacts:</div>
                                                                <div class="text"><a href="">{{$user->contacts}}<br></a></div>
                                                            </li>
                                                            <li>
                                                                <div class="title">Email:</div>
                                                                <div class="text"><a href="">{{$user->email}}</a></div>
                                                            </li>
                                                            <li>
                                                                <div class="title">Location:</div>
                                                                <div class="text">{{$user->location}}<br></div>
                                                            </li>
                                                            <li>
                                                                <div class="title">Gender:</div>
                                                                <div class="text">Male</div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pro-edit"><a class="edit-icon" href="{{ url('/admin/users/' . $user->id . '/edit') }}"><i class="fa fa-pencil"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
