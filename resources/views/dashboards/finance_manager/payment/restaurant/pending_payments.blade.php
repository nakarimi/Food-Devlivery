@extends('dashboards.support.layouts.master')
@section('title')
  Restaurants Pending Payments
@stop

@section('styles')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
@stop

@section('content')
  <div class="content container-fluid">

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          @if ($viewData['history'])
            Restaurants Payment History
          @else
            Restaurants Pending Payments
          @endif
        </div>
        <div class="card-body">
          {{-- Table filter --}}
          <div class="mb-4">
            @include('dashboards.shared.filter', [
            'route_name' => ($viewData['history']) ? 'restaurantPaymentHistory' : 'restaurantPendingPayments',
            'select' => [
            'title' => 'All Restaurants',
            'field_name' => 'restaurant_id',
            'data' => $viewData['restaurants'],
            'label_name' => 'name',
            ]
            ])
          </div>

          {{-- If there was no data for the page. --}}
          @if (count($payments) > 0 || (isset($_GET['branch_id']) && $_GET['branch_id'] > 0))
            <div class="table-responsive @if (\Request::is('pendingPayments')) pendingPayments @endif">
              <table class="table @if (\Request::is('pendingPayments')) datatable @endif">
                <thead>
                  <tr>
                    <th>Branch</th>
                    <th>Range</th>
                    <th>Total Orders</th>
                    <th>Total Income</th>
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
                      <td>{{ get_farsi_date($item->range_from) }} <b> To
                        </b>{{ get_farsi_date($item->range_to) }}</td>
                      <td>{{ $item->total_order }} @if ($item->company_order) <small
                            title="Total delivery by company is: {{ $item->company_order }}">({{ $item->company_order }})</small>
                        @endif
                      </td>
                      <td>{{ $item->total_order_income }} @if ($item->company_order_total) <small
                            title="Total money that company received, is: {{ $item->company_order_total }}">({{ $item->company_order_total }})</small>
                        @endif
                      </td>
                      <td>{{ $item->total_general_commission }}</td>
                      <td>{{ $item->total_delivery_commission }}</td>
                      <td>{{ $item->total_delivery_commission + $item->total_general_commission }}</td>
                      <td>

                        @if (\Request::is('pendingPayments'))
                          {{-- Only first payment be available for activation, so that order be consecutive. --}}
                          <form method="POST" action="{{ url('/activate_payment') }}" accept-charset="UTF-8"
                            style="display:inline">
                            {{ csrf_field() }}

                            <input type="hidden" value="{{ Request::get('branch_id') }}" name="branch_id">
                            <input type="hidden" value="{{ auth()->user()->id }}" name="reciever_id">
                            <input type="hidden" value="{{ $item->total_order }}" name="total_order">
                            <input type="hidden" value="{{ $item->total_general_commission }}"
                              name="total_general_commission">
                            <input type="hidden" value="{{ $item->total_delivery_commission }}"
                              name="total_delivery_commission">
                            <input type="hidden"
                              value="{{ $item->total_delivery_commission + $item->total_general_commission }}"
                              name="total_order_income">
                            <input type="hidden" value="{{ $item->from }}" name="range_from">
                            <input type="hidden" value="{{ $item->to }}" name="range_to">

                            <button type="submit" class="btn btn-primary btn-sm"
                              title="Once you activate, restaurants will be able to do the payments."
                              onclick="return confirm(&quot;Confirm approve?&quot;)">Acativate
                              Payment</button>
                          </form>

                        @elseif($item->status == "activated")
                          <span class="badge badge-danger" title="This means restaurant not paid yet.">Activated</span>

                        @elseif($item->status == "done")
                          <form method="POST" action="{{ url('/approve_payment') }}" accept-charset="UTF-8"
                            style="display:inline">
                            {{ csrf_field() }}
                            <input type="hidden" value="{{ $item->id }}" name="payment_id">
                            <button type="submit" class="btn btn-warning btn-sm payment_pending_btn"
                              title="This means you recieved money from restaurant."
                              onclick="return confirm(&quot;Confirm approve?&quot;)"><span>Pending</span></button>
                          </form>
                        @else
                          <span class="badge badge-success"
                            title="This means restaurant payment Approved.">Approved</span>
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
            <p class="alert alert-warning col-md-12 text-center">Select a branch / Different branch / No Record
              Found. </p>
          @endif

        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
<script src="{{ asset('js/dashboard_charts.js') }}"></script>
@stop
