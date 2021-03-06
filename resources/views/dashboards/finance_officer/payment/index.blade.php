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
               @php $searchFormURL = ''; if(\Request::is('pendingPayments')) {$searchFormURL = '/pendingPayments';}
               elseif (\Request::is('activePayments')) {$searchFormURL = '/activePayments';} else {$searchFormURL =
               '/paymentHistory';} @endphp
               <form method="GET" action="  {{ url($searchFormURL) }}" accept-charset="UTF-8"
                  class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <select class="custom-select mr-sm-2" name="branch_id" id="branch_id"
                        onchange="this.form.submit()">
                        <option value="">Select a branch</option>
                        @foreach($activeBranches as $branch)
                        <option value="{{ $branch->id }}" @if( isset($_GET['branch_id']) && $branch->id ==
                           $_GET['branch_id']) selected="selected" @endif >{{ $branch->branchDetails->title }}</option>
                        @endforeach
                     </select>
                  </div>
               </form>
               <br />
               <br />
               <br />
               {{-- If there was no data for the page. --}}
               @if( (count($payments) > 0) || (isset($_GET['branch_id']) && $_GET['branch_id'] > 0))
                  <div class="table-responsive @if (\Request::is('pendingPayments')) pendingPayments @endif">
                     <table class="table @if (\Request::is('pendingPayments')) datatable @endif">
                        <thead>
                           <tr>
                              <th>Branch</th>
                              <th>Range</th>
                              <th>Total Orders</th>
                              <th>Total Income</th>
                              <th>Company Delivery</th>
                              <th>Own Delivery</th>
                              <th>General Commission</th>
                              <th>Delivery Commission</th>
                              <th>Total Payable</th>
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse ($payments as $item)
                              <tr>
                                 <td>{{ $item->branchTitle ?? $item->branch->branchDetails->title }}</td>
                                 <td>{{ get_farsi_date($item->range_from) }} <b> To </b>{{ get_farsi_date($item->range_to) }}</td>
                                 <td>{{ $item->total_order }}</td>
                                 <td>{{ $item->total_order_income }}</td>
                                 <td><span title="Total money that company received from deliveries, is: {{$item->company_order_total}}">{{$item->company_order_total}}</span></td>
                                 <td><span title="Total money that restaurant received from deliveries, is: {{$item->own_delivery_total}}">{{$item->own_delivery_total}}</span></td>
                                 <td>{{ $item->total_general_commission }}</td>
                                 <td>{{ $item->total_delivery_commission }}</td>
                                 <td><span @if ($item->payalbe_by_restaurant - $item->payalbe_by_company > 0) class="text-success" title="This amount should be paid by restaurant to clear the balance" 
                                    @else class="text-danger" title="This amount should be paid to restaurant to clear balance" @endif>
                                    {{ abs($item->payalbe_by_restaurant - $item->payalbe_by_company) }}</span></td>
                                 <td>

                                    @if (\Request::is('pendingPayments'))
                                    {{-- Only first payment be available for activation, so that order be consecutive. --}}
                                    <form method="POST" action="{{ url('/activate_payment') }}" accept-charset="UTF-8"
                                       style="display:inline">
                                       {{ csrf_field() }}

                                       <input type="hidden" value="{{ Request::get('branch_id') }}" name="branch_id">
                                       <input type="hidden" value="{{auth()->user()->id}}" name="reciever_id">
                                       <input type="hidden" value="{{ $item->total_order }}" name="total_order">
                                       <input type="hidden" value="{{ $item->orders }}" name="orders">
                                       <input type="hidden" value="{{ $item->total_general_commission }}"
                                          name="total_general_commission">
                                       <input type="hidden" value="{{ $item->total_delivery_commission }}"
                                          name="total_delivery_commission">
                                       <input type="hidden"
                                          value="{{ $item->total_order_income }}"
                                          name="total_order_income">
                                       <input type="hidden" value="{{ $item->from }}" name="range_from">
                                       <input type="hidden" value="{{ $item->to }}" name="range_to">

                                       <button type="submit" class="btn btn-primary btn-sm"
                                          title="Once you activate, restaurants will be able to do the payments."
                                          onclick="return confirm(&quot;Confirm approve?&quot;)">Activate Payment</button>
                                    </form>

                                    @elseif($item->status == "activated")
                                    <span class="badge badge-danger"
                                       title="This means restaurant not paid yet.">Activated</span>

                                    @elseif($item->status == "paid")
                                    <form method="POST" action="{{ url('/recieve_payment') }}" accept-charset="UTF-8"
                                       style="display:inline">
                                       {{ csrf_field() }}
                                       <input type="hidden" value="{{ $item->id }}" name="payment_id">
                                       <button type="submit" class="btn btn-warning btn-sm payment_pending_btn"
                                          title="This means you received money from restaurant."
                                          onclick="return confirm(&quot;Confirm approve?&quot;)"><span>Pending</span></button>
                                    </form>
                                    @else
                                       <span class="badge badge-{{ $item->status == 'approved' ? 'success': 'warning'}}" title="{{ $item->status == 'approved' ? 'Payment approved by Finance Manager' : 'Payment paid by resaurant to the Finance Officer' }}">{{ $item->status == 'approved' ? 'Approved' : 'Received' }}</span>
                                    @endif

                                 </td>
                              </tr>

                              @empty
                              @if (!\Request::is('pendingPayments'))
                                 <p class="alert alert-warning col-md-8">No payment record found yet.</p>
                              @endif
                           @endforelse
                        </tbody>
                     </table>
                     @if (!\Request::is('pendingPayments'))
                        <div class="pagination-wrapper"> {!! $payments->appends(['search' => Request::get('search')])->render() !!} </div>
                     @endif
                  </div>

               @else 
                 <p class="alert alert-warning col-md-12 text-center">Select a branch / Different branch / No Record Found. </p>
               @endif
            </div>
         </div>
      </div>
   </div>
</div>
@endsection