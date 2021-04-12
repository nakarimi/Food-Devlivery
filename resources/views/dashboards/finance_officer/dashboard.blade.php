@extends('dashboards.restaurant.layouts.master')
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
                            <h3>{{$todayOrders}}</h3>
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
                            <h3>{{$lastSevenDaysOrders}}</h3>
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
                            <h3>{{$thisMonthOrders}}</h3>
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
                            <h3>{{$lastMonthOrders}}</h3>
                            <span>مجموعه سفارشات ماه گذشته</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title">مجموعه سفارشات به اساس حالت‌ </h3>
                                <div id="orders_status_chart"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/dashboard_charts.js')}}"></script>
@stop
