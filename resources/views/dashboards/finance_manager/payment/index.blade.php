@extends('dashboards.support.layouts.master')
@section('title')
Drivers Payments History
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Drivers Payment history</div>
            <div class="card-body">
               <a href="{{ route('driver.active_payments')}}" class="btn btn-success btn-sm" title="Add New Payment">
                  <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ route('driverPaymentHistory') }}" accept-charset="UTF-8"
                  class="form-inline my-2 my-lg-0 float-right" role="search">
                  <select class="custom-select form-control mr-sm-2" name="driver_id" id="driver_id">
                     <option value="" >Choose Driver</option>
                     @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" >{{ $driver->title }}</option>
                     @endforeach
                 </select>

                  <div class="input-group">
                     <input type="text" value="" name="date-range" class="daterange form-control">
                  </div>
                  <button class="btn btn-secondary" type="submit">
                     <i class="fa fa-search"></i>
                  </button>
               </form>
               <br />
               <br />
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Driver</th>
                           <th>Total Money Received</th>
                           <th>Finance</th>
                           <th>Receive Date</th>
                           <th>Num. Orders</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($driverPayments as $item)
                        <tr>
                           <td>{{ $item->title }}</td>
                           <td>{{ $item->total_money_received }}</td>
                           <td>{{ $item->name }}</td>
                           <td>{{ $item->created_at }}</td>
                           <td>{{ count(json_decode($item->orders_id)) }}</td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $driverPayments->appends(['search' =>
                     Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection