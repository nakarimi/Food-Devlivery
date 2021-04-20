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
                        <div class="card-header">
                            <h4 class="card-title mb-0">سفارش  <b>[{{$order->id}} - {{ translate_term($order->status) }}]</b></h4>
                        </div>
                        <a class="btn btn-warning" href="{{url('activeOrders')}}"><i class="la la-arrow-left"></i>Back</a>
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

    {{-- @Todo: This should be moved to order details. --}}
<!-- Add reject reason Modal -->
<div id="customer_details_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <hr>
            <div class="modal-body pt-0">
                {{-- <div class="row">
                    <div class="col-md-12">
                        <div class="profile-widget">
                            <div class="profile-img">
                                <a href="#" class="avatar"><img src="{{ asset('img/user.jpg') }}" alt=""></a>
                            </div>
                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="#" id="customerName">{{ $order->customer->name }}</a></h4>
                            <div class="small text-muted" id="customerEmail"></div>
                            <div class="small text-muted">+93791643460</div>
                        </div>
                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-12 text-center" >
                        @if(get_customer_status($order->customer->id) == 'blocked')
                            <button class="btn btn-danger btn-lg" data-dismiss="modal">حساب کاربری این مشتری مسدود شده است.</button>
                        @elseif(get_customer_status($order->customer->id) == 'pending')
                            <button class="btn btn-warning btn-lg" data-dismiss="modal">درخواست مسدودی این مشتری در حال بررسی است.</button>
                        @else
                            <form method="POST" action="{{ route('blockCustomer') }}" id="block_form"
                                accept-charset="UTF-8" style="display:inline" >
                                {{ csrf_field() }}
                                <input type="hidden" id="branch_id" name="branch_id">
                                <input type="hidden" id="customer_id" name="customer_id">
                                <h4>علت این درخواست؟</h4>
                                <textarea class="form-control" min  name="note" rows="4" id="blocking_note"></textarea>
                                <br>
                                <button class="btn btn-danger" type="submit" id="open_reason_button">درخواست مسدودی</button>
                            </form>
                            <button class="btn btn-success" data-dismiss="modal">لفو</button>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
@include('dashboards.restaurant.orders.scripts')

@stop
