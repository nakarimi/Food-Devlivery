<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Active Orders</div>
                <div class="card-body">
                    <a href="{{ url('/orders/create') }}" class="btn btn-success btn-sm" title="Add New Order">
                        <i class="fa fa-plus" aria-hidden="true"></i> Add New
                    </a>
{{--                    <form  accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">--}}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-append">
{{--                     <button class="btn btn-secondary" type="submit">--}}
                     <i class="fa fa-search"></i>
                     </button>
                     </span>
                        </div>
{{--                    </form>--}}
                    <br/>
                    <br/>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
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
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->branchDetails->title }}</td>
                                    <td>{{ $item->customer->name }} <br> ({{$item->reciever_phone}}) </td>
                                    <td class="max-width200">{!! show_order_itmes($item->contents) !!}</td>
                                    <td>
                                        @if($item->has_delivery == 1)
                                            @if($item->deliveryDetails->delivery_type == 'own')
                                                <span class="badge bg-inverse-success">Own Delivery</span>
                                            @else
                                                {{-- Here we show all free drivers. --}}
                                                @if($item->deliveryDetails->driver_id)
                                                    <select class="custom-select mr-sm-2" order_id={{$item->id}} name="driver_id" id="driver_id" required disabled="disabled">
                                                        @foreach($drivers as $driver)
                                                            @if($driver->id == $item->deliveryDetails->driver_id)
                                                                <option value="{{ $driver->id }}" >{{ $driver->title }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select class="custom-select mr-sm-2" order_id={{$item->id}} name="driver_id" id="driver_id" required>
                                                        <option value="" >Selece Driver</option>
                                                        @foreach($drivers as $driver)
                                                            @if($driver->status == 'free')
                                                                <option value="{{ $driver->id }}" >{{ $driver->title }}</option>
                                                            @endif

                                                        @endforeach
                                                    </select>
                                                @endif
                                            @endif
                                        @else
                                            <span class="badge bg-inverse-warning">Self Delivery</span>
                                        @endif

                                    </td>
                                    <td>
                                        <select class="custom-select mr-sm-2" order_id={{$item->id}} status="{{$item->status}}" name="order_status" id="order_status" required>
                                            <option value="pending" @if($item->status == 'pending') selected="selected" @endif >Pending</option>
                                            <option value="approved" @if($item->status == 'approved') selected="selected" @endif >Approved</option>
                                            <option value="reject" @if($item->status == 'reject') selected="selected" @endif >Reject</option>
                                            <option value="processing" @if($item->status == 'processing') selected="selected" @endif >Processing</option>
                                            <option value="delivered" @if($item->status == 'delivered') selected="selected" @endif >Delivered</option>
                                            <option value="completed" @if($item->status == 'completed') selected="selected" @endif >Complete</option>
                                            <option value="canceld" @if($item->status == 'canceld') selected="selected" @endif >Cancel</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="{{ url('/orders/' . $item->id) }}" title="View Order"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                                        <a href="{{ url('/orders/' . $item->id . '/edit') }}" title="Edit Order"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                                        <form method="POST" action="{{ url('/orders' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            {{-- <button type="submit" class="btn btn-danger btn-xs" title="Delete Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button> --}}
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $orders->appends(['search' => Request::get('search')])->render() !!} </div>
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

    var pusher = new Pusher('21bc4b7163b1064323d3', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('food-app');
    channel.bind('update-event', function(data) {
        // if (JSON.stringify(data['message']) == "Items Updated!"){
        Livewire.emit('refreshActiveOrders');
        // alert("updated!");
        // }
        // alert();
    });
</script>

