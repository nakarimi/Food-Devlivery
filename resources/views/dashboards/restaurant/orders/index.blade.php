@extends('dashboards.restaurant.layouts.master')
@section('title')
    Orders
@stop

@section('styles')
    <link rel="stylesheet" href="{{asset('css/dataTables.bootstrap4.min.css')}}">
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Orders</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @include('dashboards.restaurant.orders.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- /Add reject reason Modal -->
@endsection

@section('scripts')
@include('dashboards.restaurant.orders.scripts')
@stop
