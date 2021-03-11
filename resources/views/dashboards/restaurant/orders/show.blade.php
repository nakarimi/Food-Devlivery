@extends('dashboards.restaurant.layouts.master')
@section('title')
    Dashboard
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
                            <tr>
                                <th> Title </th>
                                <td> {{ $order->title }} </td>
                            </tr>

                            <tr>
                                <th> Branch </th>
                                <td> {{ $order->branchDetails->title }} </td>
                            </tr>

                            <tr>
                                <th> Customer </th>
                                <td> {{ $order->customer->name }} </td>
                            </tr>

                            <tr>
                                <th> Customer </th>
                                <td> {{ $order->customer->name }} </td>
                            </tr>

                            <tr>
                                <th> Delivery Option </th>
                                <td>
                                    @if(!$order->has_delivery) By Customer @endif
                                    @if($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'own') By Restaurant @endif
                                    @if($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'company') By Company @endif
                                </td>
                            </tr>

                            <tr>
                                <th> Total </th>
                                <td> {{ $order->total }} </td>
                            </tr>

                            <tr>
                                <th> Note </th>
                                <td> {{ $order->note }} </td>
                            </tr>

                            <tr>
                                <th> Satus </th>
                                <td> {{ ucfirst($order->status) }} </td>
                            </tr>

                            <tr>
                                <th> Reciever Phone </th>
                                <td> {{ $order->reciever_phone }} </td>
                            </tr>

                            <tr>
                                <th> Items </th>
                                <td>{!! show_order_itmes($order->contents) !!}</td>
                            </tr>
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
