@extends('dashboards.support.layouts.master')
@section('title')
داشبورد
@stop

@section('styles')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@section('content')
<div class="content container-fluid">

    {{-- Table of Drivers --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4>Derivers</h4>
                <sub>List of drivers who have orders money with them</sub>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('finance_manager.dashboard') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                    <div class="input-group">
                       <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                       <span class="input-group-append">
                       <button class="btn btn-secondary" type="submit">
                       <i class="fa fa-search"></i>
                       </button>
                       </span>
                    </div>
                 </form>
                 <br/>
                 <br/>
  
                @if (count($drivers))

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
                                    'url' => ['/driver_payment_recived', $deriver->id,
                                    $deriver->delivered->pluck('order_id'), $total],
                                    'style' => 'display:inline',
                                    ]) !!}
                                    <button class="btn btn-success btn-sm" type="Submit"
                                        onclick="return confirm(&quot;Do you receive the orders payment?&quot;)">Payment
                                        Recived</button>
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination-wrapper"> {!! $drivers->appends(['search' =>
                        Request::get('search')])->render() !!} </div>
                </div>
                @else
                <div class="alert alert-warning" role="alert">
                    Driver with payment not found!
                </div>
                @endif


            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{asset('js/dashboard_charts.js')}}"></script>
@stop