@extends('layouts.master')
@section('title')
   Edit Order #{{ $order->id }}
@stop
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Edit Order #{{ $order->id }}</div>
                    <div class="card-body">
                        <a href="{{ url('/orders') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/orders/' . $order->id) }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data" id="web_order_update_form" data-contents="{{ $order->contents }}">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('order.orders.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Custom JS -->
<script src="{{asset('js/order.js')}}"></script>
@endsection
