@extends('dashboards.restaurant.layouts.master')
@section('title')
    اطلاعات اصلی ({{ $branch->branchDetails->title }})
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
                                        <h3 class="user-name m-t-0 mb-0">{{$branch->branchDetails->title}}</h3>
                                        <br>
                                        <div class="staff-id">نام : {{ $branch->branchDetails->title }}</div>
                                        <div class="small doj text-muted">تاریخ  ثبت: {{ date_format(date_create($branch->created_at), "jS F Y") }}</div>
                                        <div class="staff-id">نوعیت کار : {{ $branch->business_type }} </div>
                                        <div class="staff-msg"><a class="btn btn-custom" href="{{route('branch.edit', $branch->id)}}">تغیر اطلاعات</a></div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <ul class="personal-info">
                                        <li>
                                            <div class="title">شماره تماس:</div>
                                            <div class="text"><a href="">{{ $branch->branchDetails->contact }}</a></div>
                                        </li>
                                        <li>
                                            <div class="title">موقعیت:</div>
                                            <div class="text">{{ $branch->branchDetails->location }} </div>
                                        </li>
                                        <li>
                                            <div class="title">نرخ کمیشن عمومی:</div>
                                            <div class="text">{{ $branch->mainCommission->title }}</div>
                                        </li>
                                        @if (isset($branch->deliver_commission_id))
                                                <li>
                                                    <div class="title">نرخ کمیشن پیک:</div>
                                                    <div class="text">{{ $branch->deliveryCommission->title }}</div>
                                                </li>
                                        @endif

                                        <li>
                                            <div class="title">:جزئیات</div>
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

    <br>
        <div class="table-responsive">
            <h1>همه تغیرات</h1>
            <table class="table table-striped">
                <thead>
                    <th>شماره</th>
                    <th>نام</th>
                    <th>تماس</th>
                    <th>موقعیت</th>
                    <th>جزئیات</th>
                    <th>وضعیت</th>
                    <th>یادداشت</th>
                </thead>
                <tbody>
                @foreach($branch->branchFullDetails as $detail)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$detail->title}}</td>
                        <td>{{$detail->contact}}</td>
                        <td>{{$detail->location}}</td>
                        <td>{{$detail->description}}</td>
                        <td>
                            @if ($detail->status == "old" )
                                <span class="badge bg-inverse-default">قدیمی</span>
                            @elseif ($detail->status == "pending" )
                                <span class="badge bg-inverse-warning">معطل</span>
                            @elseif ($detail->status == "rejected" )
                                <span class="badge bg-inverse-danger">رد شده</span>
                            @else
                                <span class="badge bg-inverse-success">تائید شده</span>
                            @endif
                        </td>
                        <td>{{$detail->note}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
@endsection

@section('scripts')

@stop




