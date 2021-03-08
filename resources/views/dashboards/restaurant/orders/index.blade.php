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
{{--                        <a class="btn btn-success" href="{{route('menu.create')}}">Add New Menu</a>--}}
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Branch</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Phone</th>
                                <th>Delivery</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $item)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->branchDetails->title }}</td>
                                    <td>{{ $item->customer->name }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>{{ $item->reciever_phone }}</td>
                                    <td>
                                        @if($item->has_delivery == 1)
                                            @if($item->deliveryDetails->delivery_type == 'own')
                                                <span class="badge bg-inverse-success">Own Delivery</span>
                                            @else
                                                <span class="badge bg-inverse-primary">Company Delivery</span>
                                            @endif
                                        @else
                                            <span class="badge bg-inverse-warning">Self Delivery2</span>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="custom-select mr-sm-2" order_id={{$item->id}} status="{{$item->status}}" name="order_status" id="order_status" required>
                                            <option value="pending" @if($item->status == 'pending') selected="selected" @endif >Pending</option>
                                            <option value="approved" @if($item->status == 'approved') selected="selected" @endif >Approved</option>
                                            <option value="reject" @if($item->status == 'reject') selected="selected" @endif >Reject</option>
                                            <option value="processing" @if($item->status == 'processing') selected="selected" @endif >Processing</option>
                                            <option value="delivered" @if($item->status == 'delivered') selected="selected" @endif >Delivered</option>
                                            <option value="completed" @if($item->status == 'completed') selected="selected" @endif >Complete</option>
                                            <option value="canceld" @if($item->status == 'canceld') selected="selected" @endif >Cancel</option>
                                        </select>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Datatable JS -->
    <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('js/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Custom JS -->
    <script src="{{asset('js/commmon_functions.js')}}"></script>
    <script src="{{asset('js/order.js')}}"></script>
@stop
