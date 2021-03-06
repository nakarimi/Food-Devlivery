
@section('title')
    {{$pageTitle}}
@stop
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{$pageTitle}}</div>
                <div class="card-body">
                    @include('order.orders.order-filter')
                   <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Code</th>
                                <th>Time</th>
                                <th>Branch</th>
                                <th>Customer</th>
                                <th>Contents</th>
                                <th>Delivery</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $item)
                                <tr>
                                    <td>{{ $item->id}}</td>
                                    <td>{{ $item->created_at->diffForHumans() }}</td>
                                    <td>{{ $item->branchDetails->title }} <br> ({{$item->branchDetails->contact}}) </td>
                                    <td>{{ $item->customer->name }} <br> ({{$item->customer->reciever_phone}}) </td>
                                    <td class="max-width200">{!! show_order_itmes($item->contents) !!}</td>
                                    <td class='@if (($item->deliveryDetails->delivery_type == "own")) hasDriver @endif '>
                                        @if($item->deliveryDetails->delivery_type == 'own')
                                                <span class="badge bg-inverse-success">Own Delivery</span>
                                        @else
                                            @if($item->deliveryDetails->driver)

                                                <span class="badge bg-inverse-primary">(Company Delivery) <br>
                                                    <span class="badge bg-inverse-danger">{{$item->deliveryDetails->driver->title}}</span>
                                                </span>

                                            @else
                                               <select class="custom-select mr-sm-2 driver_select" order_id={{$item->id}} name="driver_id" id="driver_id" customer_id="{{ $item->customer_id }}" required>
                                                        
                                                    @if(count($drivers) > 0)
                                                        <option value="" disabled selected >Selece Driver</option>
                                                    @endif

                                                    @forelse($drivers as $driver)
                                                        
                                                        @if($driver->status == 'free')
                                                            <option value="{{ $driver->id }}" status="free">{{ $driver->title }} </option>
                                                        @elseif($driver->status == 'busy')
                                                            <option value="{{ $driver->id }}" status="busy">{{ $driver->title }} </option>
                                                        @endif

                                                    @empty
                                                        <option value="" disabled selected >Driver N/A</option>
                                                    @endforelse

                                                </select>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <select class="custom-select mr-sm-2" order_id={{$item->id}} status="{{$item->status}}" name="order_status" id="order_status" required>
                                            <option value="pending" @if($item->status == 'pending') selected="selected" @endif >Pending</option>
                                            <option value="reject" @if($item->status == 'reject') selected="selected" @endif >Reject</option>
                                            <option value="processing" @if($item->status == 'processing') selected="selected" @endif >Processing</option>
                                            <option value="delivered" @if($item->status == 'delivered') selected="selected" @endif >Delivered</option>
                                            <option value="completed" @if($item->status == 'completed') selected="selected" @endif >Complete</option>
                                            <option value="canceld" @if($item->status == 'canceld') selected="selected" @endif >Cancel</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="{{ url('/orders/' . $item->id) }}" title="View Order"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/orders/' . $item->id . '/edit') }}" title="Edit Order"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                        <form method="POST" action="{{ url('/orders' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            {{-- <button type="submit" class="btn btn-danger btn-sm" title="Delete Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button> --}}
                                        </form>

                                        @if($item->tracking_by && $item->tracking_by->status == 0)
                                            <a title="Following by {{$item->tracking_by->support->name}}">
                                                <button class="btn btn-dark btn-sm followup" cancelId="{{$item->tracking_by->id}}">
                                                    <i class="fa fa-spinner fa-spin" aria-hidden="true" ></i>
                                                </button>
                                            </a>
                                        @else
                                            <a title="Mark this as checking...."><button class="btn btn-success btn-sm followup" order_id={{$item->id}} support_id={{auth()->user()->id}}><i class="fa fa-spinner"  ></i></button></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> 
                            {!! $orders->appends(['search' => Request::get('search')])->render() !!} 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom JS -->
<script src="{{asset('js/order.js')}}"></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    // Since we are using one blade fro active and pending orders, then this mapping is need to consider for separate pages.
    let page = (location.pathname.substring(1) == 'activeOrders') ? 'refreshActiveOrders' : 'refreshWaitingOrder';

    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
    });

    var channel = pusher.subscribe('food-app');
    channel.bind('update-event', function(data) {
        Livewire.emit(page);
    });
</script>

