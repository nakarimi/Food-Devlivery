<table class="table table-striped mb-0 datatable">
    <thead>
        <tr>
            <th>کد سفارش</th>
            <th class="disable_sort">زمان تحویل دهی </th>
            <th>مجموعه</th>
            <th class="disable_sort">نوعیت انتقال</th>
            <th class="disable_sort">غذا ها</th>
            <th>حالت</th>
            <th class="disable_sort">تغیرات</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $item)
            <tr class="{{is_order_late($item->timeDetails->promissed_time, $item->status)}}">
                <td>{{ $item->id }}</td>
                <td> {{get_promissed_date($item->timeDetails->promissed_time)}} </td>
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
                 
                    @if(($item->status == "canceld" || $item->status == "completed")) 
                         <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_status($item->status)}}
                        </span>
                    @elseif($item->status == "pending")
                        <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_status($item->status)}}
                            <div class="tooltip status">
                                <button type="button" id="order_approve_btn" order_id="{{ $item->id }}" customer_id="{{ $item->customer_id }}" class="btn btn-success order_confirm_processing_btn" value="processing" data-toggle="modal" data-target="#add_order_completion_time" >قبول</button>
                                <button type="button" id="order_reject_btn" order_id="{{ $item->id }}" customer_id="{{ $item->customer_id }}" class="btn btn-danger order_reject_btn" value="reject" data-toggle="modal" data-target="#add_order_reject_reason">رد</button>
                            </div>
                        
                        </span>
                    @else

                        <span class="badge bg-inverse hover" status="{{$item->status}}">
                            {{translate_status($item->status)}}
                        </span>

                    @endif
                </td>
                <td><a href="{{ url('/orders/' . $item->id) }}" title="View Order"><button
                            class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- @Todo: This should be moved to order details. --}}
<!-- Add reject reason Modal -->
<div id="customer_details_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <hr>
            <div class="modal-body pt-0">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-widget">
                            <div class="profile-img">
                                <a href="#" class="avatar"><img src="{{ asset('img/user.jpg') }}" alt=""></a>
                            </div>
                            <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="#" id="customerName">Mike
                                    Litorus</a></h4>
                            <div class="small text-muted" id="customerEmail"></div>
                            <div class="small text-muted">+93791643460</div>
                        </div>
                    </div>
                </div>
                <div class="row d-none" id="textarea_div">
                    <div class="form-group col-md-12">
                        <label>Any Blocking Reason?</label>
                        <textarea class="form-control" rows="4" id="blocking_note"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-danger" id="open_reason_button">Block</button>
                        <form method="POST" action="{{ route('blockCustomer') }}" id="block_form"
                            accept-charset="UTF-8" style="display:inline">
                            {{ csrf_field() }}
                            <input type="hidden" id="branch_id" name="branch_id">
                            <input type="hidden" id="customer_id" name="customer_id">
                            <input type="hidden" id="order_id" name="order_id">
                            <input type="hidden" id="note" name="note">
                        </form>
                        <button class="btn btn-success" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
