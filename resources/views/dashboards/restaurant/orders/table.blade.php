<table class="table table-striped mb-0">
    <thead>
        <tr>
            <th>کد سفارش</th>
            <th>زمان ثبت/ تحویل دهی</th>
            <th>مجموعه</th>
            <th class="disable_sort">نوعیت انتقال</th>
            <th class="disable_sort">غذا ها</th>
            <th>حالت</th>
            <th class="disable_sort">تغیرات</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $item)
            <tr class="{{ !empty($item->timeDetails->promissed_time) ? is_order_late($item->timeDetails->promissed_time, $item->status) : ''}}">
                <td>{{ $item->id }}</td>
                <td> {{ !empty($item->timeDetails->promissed_time) ? get_promissed_date($item->timeDetails->promissed_time) : get_promissed_date($item->created_at)}} </td>
                <td>{{ $item->total }}</td>
                <td>
                    @if ($item->has_delivery == 1)
                        @if ($item->deliveryDetails->delivery_type == 'own')
                            <span class="badge bg-inverse-success hover">Own Delivery
                                <div class="tooltip">
                                    <button type="button" order_id="{{ $item->id }}" class="btn .btn-default request_delivery_btn" title="درخواست سرویس پیک برای این سفارش">درخواست پیک</button>
                                </div>
                            </span>
                        @else
                            <span class="badge bg-inverse-primary">(Company Delivery) <br>
                                <span
                                    class="badge bg-inverse-danger">{{ $item->deliveryDetails->driver->title ?? 'Pending' }}</span>
                            </span>
                        @endif
                    @else
                        <span class="badge bg-inverse-warning">Self Delivery</span>
                    @endif
                </td>
                <td>{!! show_order_itmes($item->contents) !!}</td>
                <td>
                 
                    @if(($item->status == "canceld" || $item->status == "completed" || $item->status == "reject")) 
                         <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_term($item->status)}}
                        </span>
                    @elseif($item->status == "pending")
                        <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_term($item->status)}}
                            <div class="tooltip status">
                                <button type="button" order_id="{{ $item->id }}" customer_id="{{ $item->customer_id }}" class="btn btn-success order_confirm_processing_btn order_approve_btn" value="processing" data-toggle="modal" data-target="#add_order_completion_time" >قبول</button>

                                <button type="button" order_id="{{ $item->id }}" customer_id="{{ $item->customer_id }}" class="btn btn-danger order_reject_btn" value="reject" data-toggle="modal" data-target="#add_order_reject_reason">رد</button>
                            </div>
                        
                        </span>
                    @else

                        <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_term($item->status)}}
                        </span>

                    @endif
                </td>
                <td><a href="{{ url('/orders/' . $item->id) }}" title="View Order"><button
                            class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<div class="pagination-wrapper"> 
    {!! $orders->appends(['search' => Request::get('search')])->render() !!} 
</div>
