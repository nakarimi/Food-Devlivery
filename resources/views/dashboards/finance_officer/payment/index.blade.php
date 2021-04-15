@extends('dashboards.support.layouts.master')
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
               <form method="GET" action="{{ url('/pendingPayments') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <select class="custom-select mr-sm-2" name="branch_id" id="branch_id" onchange="this.form.submit()" >
                     <option value="">Select a brach</option>
                        @foreach($activeBranches as $branch)
                           <option value="{{ $branch->id }}" @if( isset($_GET['branch_id']) && $branch->id == $_GET['branch_id']) selected="selected" @endif >{{ $branch->branchDetails->title }}</option>
                        @endforeach
                  </select>
                  </div>
               </form>
               <br/>
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
                        @forelse ($payments as $item)
                        <tr>
                           <td>{{ $item->range_from }} <b> To </b>{{ $item->range_to }}</td>
                           <td>{{ $item->totalOrders }}</td>
                           <td>{{ $item->totalOrdersPrice }}</td>
                           <td>{{ $item->totalGeneralCommission }}</td>
                           <td>{{ $item->totalDeliveryCommission }}</td>
                           <td>{{ $item->totalDeliveryCommission + $item->totalGeneralCommission }}</td>
                            <td>
                               
                            </td>
                        </tr>

                        @empty
                           <p class="alert alert-warning">Select a branch</p>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
