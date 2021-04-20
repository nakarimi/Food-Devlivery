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

               {{-- Table filter --}}
               @include('dashboards.shared.filter', [
                  'route_name' => 'driverPaymentHistory',
                  'select' => [
                     'title' => 'Choose Driver',
                     'field_name' => 'driver_id',
                     'data' => $drivers,
                     'label_name' => 'title',
                  ]
               ])

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