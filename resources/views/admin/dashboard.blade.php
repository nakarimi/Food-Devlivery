@extends('layouts.master')

{{-- title of the page --}}
@section('title')
  Admin Dashboard
@stop

{{-- Styles of the page --}}
@section('styles')

  <!-- Chart CSS -->
  <link rel="stylesheet" href="{{ asset('plugins/morris/morris.css') }}">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

{{-- Page content --}}
@section('content')
  <!-- Page Header -->
  <div class="page-header">
    <div class="row">
      <div class="col-sm-12">
        <h3 class="page-title">Welcome Admin!</h3>
        <ul class="breadcrumb">
          <li class="breadcrumb-item active">Dashboard</li>
        </ul>
      </div>
    </div>
  </div>
  <br>

  <!-- /Page Header -->
  <h6>Restaurants Income in 10 days</h6>
  <div id="chart_div" style="height: 500px"></div>

  <br>
  <br>
  <br>
  <h6>Today Orders</h6>
  <div class="row">
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-shopping-basket"></i></span>
          <div class="dash-widget-info">
            <h3>{{ @$data['all_orders'] }}</h3>
            <span>All</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-motorcycle"></i></span>
          <div class="dash-widget-info">
            <h3>{{ @$data['all_derlivered'] }}</h3>
            <span>Delivered</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-money"></i></span>
          <div class="dash-widget-info">
            <h3>{{ @$data['total_income'] }}</h3>
            <span>Total Income</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-bitcoin"></i></span>
          <div class="dash-widget-info">
            <h3>{{ @$data['total_commission'] }}</h3>
            <span>Total Commission</span>
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
              <h3 class="card-title">All Orders Based on Status </h3>
              <div id="orders_status_chart"></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
          <div class="dash-widget-info">
            <h3>{{ $data['totalRestaurants'] }}</h3>
            <span>Total Restaurants</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-car"></i></span>
          <div class="dash-widget-info">
            <h3>{{ $data['totalDrivers'] }}</h3>
            <span>Total Drivers</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-legal"></i></span>
          <div class="dash-widget-info">
            <h3>{{ $data['totalOrders'] }}</h3>
            <span>Total Orders</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
      <div class="card dash-widget">
        <div class="card-body">
          <span class="dash-widget-icon"><i class="fa fa-list"></i></span>
          <div class="dash-widget-info">
            <h3>{{ $data['totalItems'] }}</h3>
            <span>Total Items</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card-group m-b-30">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div>
                <span class="d-block">Total Orders Today</span>
              </div>
              <div>

                <span
                  class={{ format_percentage(calculate_percentage($data['yesterdayOrders'], $data['todayOrders']))[1] }}>
                  {{ format_percentage(calculate_percentage($data['yesterdayOrders'], $data['todayOrders']))[0] }}%
                </span>
              </div>
            </div>
            <h3 class="mb-3">{{ $data['todayOrders'] }}</h3>
            <div class="progress mb-2" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="mb-0">Orders Yesterday {{ $data['yesterdayOrders'] }}</p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div>
                <span class="d-block">Total Orders This Week</span>
              </div>
              <div>
                <span
                  class={{ format_percentage(calculate_percentage($data['lastWeekOrders'], $data['thisWeekOrders']))[1] }}>
                  {{ format_percentage(calculate_percentage($data['lastWeekOrders'], $data['thisWeekOrders']))[0] }}%
                </span>
              </div>
            </div>
            <h3 class="mb-3">{{ $data['thisWeekOrders'] }}</h3>
            <div class="progress mb-2" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="mb-0">Previous Week <span class="text-muted">{{ $data['lastWeekOrders'] }}</span></p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div>
                <span class="d-block">Total Orders This Month</span>
              </div>
              <div>
                <span
                  class={{ format_percentage(calculate_percentage($data['lastMonthOrders'], $data['thisMonthOrders']))[1] }}>
                  {{ format_percentage(calculate_percentage($data['lastMonthOrders'], $data['thisMonthOrders']))[0] }}%
                </span>
              </div>
            </div>
            <h3 class="mb-3">{{ $data['thisMonthOrders'] }}</h3>
            <div class="progress mb-2" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="mb-0">Previous Month <span class="text-muted">{{ $data['lastMonthOrders'] }}</span></p>
          </div>
        </div>

        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
              <div>
                <span class="d-block">Total Orders This Year</span>
              </div>
              <div>
                <span
                  class={{ format_percentage(calculate_percentage($data['lastYearOrders'], $data['thisYearOrders']))[1] }}>
                  {{ format_percentage(calculate_percentage($data['lastYearOrders'], $data['thisYearOrders']))[0] }}%
                </span>
              </div>
            </div>
            <h3 class="mb-3">{{ $data['thisYearOrders'] }}</h3>
            <div class="progress mb-2" style="height: 5px;">
              <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p class="mb-0">Previous Year <span class="text-muted">{{ $data['lastYearOrders'] }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

{{-- Scripts of the page --}}
@section('scripts')
  <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
  <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>

  <script src="{{ asset('js/chart.js') }}"></script>
  <script src="{{ asset('js/dashboard_charts.js') }}"></script>
@stop
