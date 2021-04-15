@extends('dashboards.support.layouts.master')
@section('title')
داشبورد
@stop

@section('styles')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@section('content')
<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                {{--                    <h3 class="page-title">خوش امدی {{auth()->user()->name}}!</h3>--}}
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active">داشبورد</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->

    <div class="row">
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="card dash-widget">
                <div class="card-body">
                    <span class="dash-widget-icon"><i class="fa fa-cubes"></i></span>
                    <div class="dash-widget-info">
                        <h3>{{@$todayOrders}}</h3>
                        <span>سفارشات امروز</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="card dash-widget">
                <div class="card-body">
                    <span class="dash-widget-icon"><i class="fa fa-usd"></i></span>
                    <div class="dash-widget-info">
                        <h3>{{@$lastSevenDaysOrders}}</h3>
                        <span>سفارشات ۷ روز اخر</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="card dash-widget">
                <div class="card-body">
                    <span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
                    <div class="dash-widget-info">
                        <h3>{{@$thisMonthOrders}}</h3>
                        <span> مجموعه سفارشات همین ماه</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
            <div class="card dash-widget">
                <div class="card-body">
                    <span class="dash-widget-icon"><i class="fa fa-user"></i></span>
                    <div class="dash-widget-info">
                        <h3>{{@$lastMonthOrders}}</h3>
                        <span>مجموعه سفارشات ماه گذشته</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Table of Drivers --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Derivers</div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped custom-table mb-0 table-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Deriver Name</th>
                                <th>Num-Orders</th>
                                <th>Delivery Commission</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($drivers as $deriver)
                            @php
                            $commission = 0;
                            $total = 0;
                            foreach ($deriver->delivered as $item){
                                $commission += $item->order->commission_value;
                                $total += $item->order->total;
                            }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $deriver->title }}</td>
                                <td>{{ count($deriver->delivered) }}</td>
                                <td>{{ $commission }}</td>
                                <td>{{ $total }}</td>
                                <td>
                                    {!! Form::open([
                                    'method' => 'post',
                                    'url' => ['/driverPaymentRecived', $deriver->id, $deriver->delivered->pluck('id')],
                                    'style' => 'display:inline',
                                    ]) !!}
                                    <button class="btn btn-success btn-sm" type="Submit"
                                        onclick="return confirm(&quot;Do you receive the orders payment?&quot;)">Payment Recived</button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-wrapper"> {!! $drivers->appends(['search' =>
                        Request::get('search')])->render() !!} </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('js/dashboard_charts.js')}}"></script>
@stop