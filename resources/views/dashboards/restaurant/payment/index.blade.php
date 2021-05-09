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
                           <th>جمعی شرکت</th>
                           <th>جمعی رستوران</th>
                           <th>کمیشن عمومی</th>
                           <th>کمیشن پیک </th>
                           <th>قابل پرداخت</th>
                           <th>مجموع تحویلی</th>
                           <th>تحویل گیرنده</th>
                           <th>#</th>
                         </tr>
                       </thead>
                       <tbody>
                         @foreach ($payments as $item)
                           <tr>
                             <td>{{ get_farsi_date($item->range_from) . ' الی ' . get_farsi_date($item->range_to) }}</td>
                             <td>{{ $item->total_order }}</td>
                             <td>{{ $item->total_order_income }}</td>
                             <td><span
                                 title="مجموع پول های جمع شده توسط شرکت: {{ $item->company_order_total }}">{{ $item->company_order_total }}</span>
                             </td>
                             <td><span
                                 title="مجموع پول های جمع شده توسط رستوران: {{ $item->own_delivery_total }}">{{ $item->own_delivery_total }}</span>
                             </td>
                             <td>{{ $item->total_general_commission }}</td>
                             <td>{{ $item->total_delivery_commission }}</td>
                             <td><span @if ($item->payalbe_by_restaurant - $item->payalbe_by_company > 0) class="text-danger" title="این مبلغ باید به شرکت پرداخت شود." 
                                     @else class="text-success" title="این مبلغ باید از شرکت دریافت شود." @endif>
                                 {{ abs($item->payalbe_by_restaurant - $item->payalbe_by_company) }}</span></td>
                             <td>
                                  <td> {{ $item->user->name }} </td>
                           <td>
                              @if($item->status == 'activated')
                                 <form method="POST" action="{{ url('/pay') }}" accept-charset="UTF-8" style="display:inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" value="{{ $item->id }}" name="payment_id">
                                    <button type="submit" class="btn btn-success btn-sm" title="تسویه‌حساب." onclick="return confirm(&quot;تسویه‌حساب را تائید میکنید؟&quot;)">تسویه‌حساب</button>
                                 </form>
                              @elseif($item->status == 'paid')
                                 <span class="badge badge-warning" >انتظار</span>
                              @elseif($item->status == 'approved')
                                 <span class="badge badge-info" title="تسویه‌حساب تائید نهایی شده توسط فایننس منیجر.">تسویه‌حساب تائید نهایی شده</span>
                              @else 
                                 <span class="badge badge-success" title="تسویه‌حساب نیاز به تائید توسط فایننس منیجر دارد.">تسویه‌حساب تائید شده</span>
                              @endif
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
