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
                   <h5 class="page-title">Welcome <b>{{auth()->user()->name}}</h5>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="row">
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$paymentData['totalActivePayments']}}</h3>
                            <span>Active Payments</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-car"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$paymentData['totalActiveRestaurants']}}</h3>
                            <span>Active Restaurants</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-legal"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$paymentData['totalPendingPayments']}}</h3>
                            <span>Total Pending</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                <div class="card dash-widget">
                    <div class="card-body">
                        <span class="dash-widget-icon"><i class="fa fa-list"></i></span>
                        <div class="dash-widget-info">
                            <h3>{{$paymentData['totalPaidRestaurants']}}</h3>
                            <span>Processed Restaurants </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Finance Summary</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Total Orders</th>
                                        <th>Total Income</th>
                                        <th>Total Asked</th>
                                        <th>Total Paid</th>
                                        <th>Total Recieved</th>
                                        <th>Total Restaurant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$paymentData['financial_summary']['total_order']}}</td>
                                        <td>{{$paymentData['financial_summary']['total_order_income']}} (AF)</td>
                                        <td>{{$paymentData['financial_summary']['total_order_income']}} (AF)</td>
                                        <td>{{$paymentData['financial_summary']['total_paid']}} (AF)</td>
                                        <td>{{$paymentData['financial_summary']['total_recieved']}} (AF)</td>
                                        <td>{{$paymentData['totalActiveRestaurants']}}</td>
                                    </tr>
                                </tbody>
                            </table>
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
