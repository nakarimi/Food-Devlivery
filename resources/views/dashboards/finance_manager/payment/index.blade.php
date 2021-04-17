@extends('dashboards.support.layouts.master')
@section('title')
Driver Payments History
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Payment history for <strong>{{ $driver->title }}</strong></div>
            <div class="card-body">
               <a href="{{ url('/payment/create') }}" class="btn btn-success btn-sm" title="Add New Payment">
                  <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/payment') }}" accept-charset="UTF-8"
                  class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <input type="text" class="form-control" name="search" placeholder="Search..."
                        value="{{ request('search') }}">
                     <span class="input-group-append">
                        <button class="btn btn-secondary" type="submit">
                           <i class="fa fa-search"></i>
                        </button>
                     </span>
                  </div>
               </form>
               <br />
               <br />
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Total Money Received</th>
                           <th>Finance</th>
                           <th>Receive Date</th>
                           <th>Num. Orders</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($driverPayments as $item)
                        <tr>
                           <td>{{ $item->total_money_received }}</td>
                           <td>{{ $item->name }}</td>
                           <td>{{ $item->created_at }}</td>
                           <td>{{ count(json_decode($item->orders_id)) }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  {{-- <div class="pagination-wrapper"> {!! $driverPayments->appends(['search' =>
                     Request::get('search')])->render() !!} </div> --}}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection