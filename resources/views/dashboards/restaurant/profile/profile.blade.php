@extends('dashboards.restaurant.layouts.master')
@section('title')
    Your Profile
@stop

@section('styles')
@stop

@section('content')
    <div class="card mb-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-view">
                        <div class="profile-img-wrap">
                            <div class="profile-img">
                                <a href="#"><img alt="" src="{{ url('storage/profile_images/'.$branch->branchDetails->logo) }}"></a>
                            </div>
                        </div>
                        <div class="profile-basic">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="profile-info-left">
                                        <h3 class="user-name m-t-0 mb-0">{{auth()->user()->name}}</h3>
                                        <br>
                                        <div class="staff-id">Branch Title : {{ $branch->branchDetails->title }}</div>
                                        <div class="small doj text-muted">Registered on: {{ date_format(date_create($branch->created_at), "jS F Y") }}</div>
                                        <div class="staff-id">Business Type : {{ $branch->business_type }} </div>
                                        <div class="staff-msg"><a class="btn btn-custom" href="{{route('branch.edit', $branch->id)}}">Edit Details</a></div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">Phone:</div>
                                            <div class="text"><a href="">{{ $branch->branchDetails->contact }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">Location:</div>
                                            <div class="text">{{ $branch->branchDetails->location }} </div>
                                        </li>
                                        <li>
                                            <div class="title">Main Commission:</div>
                                            <div class="text">{{ $branch->mainCommission->title }}</div>
                                        </li>
                                        @if (isset($branch->deliver_commission_id))
                                                <li>
                                                    <div class="title">Delivery Commission:</div>
                                                    <div class="text">{{ $branch->deliveryCommission->title }}</div>
                                                </li>
                                        @endif

                                        <li>
                                            <div class="title">Description</div>
                                            <div class="text">{{ $branch->branchDetails->description }}</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="pro-edit"><a data-target="#profile_info" data-toggle="modal" class="edit-icon" href="{{route('branch.edit', $branch->id)}}"><i class="fa fa-pencil"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@stop




