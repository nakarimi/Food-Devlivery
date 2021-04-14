@extends('layouts.master')
@section('title')
   Payments
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Payment</div>
            <div class="card-body">
               <a href="{{ url('/payment/create') }}" class="btn btn-success btn-sm" title="Add New Payment">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/payment') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Range</th>
                           <th>Total Orders</th>
                           <th>Total Income</th>
                            <th>Total General Commission</th>
                            <th>Total Delivery Commission</th>
                            <th>Total Payable</th>
                           <th>Actions</th>
                        </tr>
                     </thead>
                    <tbody>
                        @foreach($payments as $item)
                        <tr>
                           <td>{{ $item->range_from .' To ' . $item->range_to }}</td>
                           <td>{{ $item->totalOrders }}</td>
                           <td>{{ $item->totalOrdersPrice }}</td>
                           <td>{{ $item->totalGeneralCommission }}</td>
                           <td>{{ $item->totalDeliveryCommission }}</td>
                           <td>{{ $item->totalDeliveryCommission + $item->totalGeneralCommission }}</td>
                            <td>
                               
                            </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $payments->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
