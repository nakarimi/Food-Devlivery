@extends('dashboards.restaurant.layouts.master')
@section('title')
   Payments
@stop
@section('content')
<div class="container">
   {{-- <div class="row">
      <div class="col-md-3">
         <div class="stats-info">
            <h6>همه سفارشات</h6>
            <h4>12 <span>This Month</span></h4>
         </div>
      </div>
      <div class="col-md-3">
         <div class="stats-info">
            <h6>همه ارسال ها</h6>
            <h4>3 <span>This Month</span></h4>
         </div>
      </div>
      <div class="col-md-3">
         <div class="stats-info">
            <h6>Renewal</h6>
            <h4>0 <span>Next Month</span></h4>
         </div>
      </div>
      <div class="col-md-3">
         <div class="stats-info">
            <h6>Total Companies</h6>
            <h4>312</h4>
         </div>
      </div>
   </div> --}}

   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Payment</div>
            <div class="card-body">
               <br/>
               <div class="table-responsive">
                  <table class="table table-striped mb-0 ">
                     <thead>
                        <tr>
                           <th>تاریخ</th>
                           <th>تعداد سفارش</th>
                           <th>مجموع قیمت</th>
                           <th>کمیشن عمومی</th>
                           <th>کمیشن پیک </th>
                           <th>مجموع تحویلی</th>
                           <th>#</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($payments as $item)
                        <tr>
                           <td>{{ $item->range_from .' الی ' . $item->range_to }}</td>
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
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('scripts')
@stop
