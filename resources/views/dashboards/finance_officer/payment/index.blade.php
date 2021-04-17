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
            @php $searchFormURL = ''; if(\Request::is('pendingPayments')) {$searchFormURL = '/pendingPayments';}  elseif (\Request::is('activePayments')) {$searchFormURL = '/activePayments';}  else {$searchFormURL = '/paymentHistory';} @endphp
               <form method="GET" action="  {{ url($searchFormURL) }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                           <th>Branch</th>
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
                              <td>{{ $item->branchTitle ?? $item->branch->branchDetails->title }}</td>
                              <td>{{ $item->range_from }} <b> To </b>{{ $item->range_to }}</td>
                              <td>{{ $item->total_order }}</td>
                              <td>{{ $item->total_order_income }}</td>
                              <td>{{ $item->total_general_commission }}</td>
                              <td>{{ $item->total_delivery_commission }}</td>
                              <td>{{ $item->total_delivery_commission + $item->total_general_commission }}</td>
                              <td>

                              @if (\Request::is('pendingPayments'))
                                 {{-- Only first payment be available for activation, so that order be consecutive. --}}
                                 <form method="POST" action="{{ url('/activate_payment') }}" accept-charset="UTF-8" style="display:inline">
                                       {{ csrf_field() }}
                                       
                                       <input type="hidden" value="{{ Request::get('branch_id') }}" name="branch_id">
                                       <input type="hidden" value="{{auth()->user()->id}}" name="reciever_id">
                                       <input type="hidden" value="{{ $item->total_order }}" name="total_order">
                                       <input type="hidden" value="{{ $item->total_general_commission }}" name="total_general_commission">
                                       <input type="hidden" value="{{ $item->total_delivery_commission }}" name="total_delivery_commission">
                                       <input type="hidden" value="{{ $item->total_delivery_commission + $item->total_general_commission }}" name="total_order_income">
                                       <input type="hidden" value="{{ $item->from }}" name="range_from">
                                       <input type="hidden" value="{{ $item->to }}" name="range_to">

                                       <button type="submit" class="btn btn-primary btn-sm" title="Once you activate, restaurants will be able to do the payments." onclick="return confirm(&quot;Confirm approve?&quot;)">Acativate Payment</button>
                                    </form>

                              @elseif($item->status == "activated")
                                 <span class="badge badge-danger" title="This means restaurant not paid yet.">Activated</span>

                              @elseif($item->status == "paid")
                                 <form method="POST" action="{{ url('/recieve_payment') }}" accept-charset="UTF-8" style="display:inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $item->id }}" name="payment_id">
                                    <button type="submit" class="btn btn-warning btn-sm" title="This means you recieved money from restaurant." onclick="return confirm(&quot;Confirm approve?&quot;)">Pending</button>
                                 </form>
                              @else
                                 <span class="badge badge-success" title="This means restaurant not paid yet.">Paid</span>
                              @endif
                              
                              </td>
                           </tr>

                        @empty
                           @if (\Request::is('pendingPayments'))
                              <p class="alert alert-warning">Select a branch / different branch </p>
                           @else
                              <p class="alert alert-warning">No payment record yet.</p>
                           @endif
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
