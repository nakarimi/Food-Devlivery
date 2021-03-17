@extends('dashboards.restaurant.layouts.master')
@section('title')
    Order 
@stop

@section('styles')
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">{{ $order->title }} <b>[{{ ucfirst($order->status) }}]</b></h4>
                    <a class="btn btn-warning" href="{{route('orders.index')}}"><i class="la la-arrow-left"></i>Back</a>
                    </div>
                    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            @include('order.orders.show-details')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

@stop
