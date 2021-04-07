@extends('layouts.master')

@section('title')
   Orders History
@stop

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Orders</div>
            <div class="card-body">
               
               <div class="table-responsive">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>Order Code</th>
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
                           <td>{{ $item->branchDetails->title }} <br> ({{$item->branchDetails->contact}}) </td>
                           <td>{{ $item->customer->name }} <br> ({{$item->reciever_phone}}) </td>
                           <td class="max-width200">{!! show_order_itmes($item->contents) !!}</td>
                           <td>
                                @if($item->has_delivery == 1)
                                    @if($item->deliveryDetails->delivery_type == 'own')
                                       <span class="badge bg-inverse-success">Own Delivery</span>
                                    @else
                                       <span class="badge bg-inverse-primary">(Company Delivery) <br>
                                          <span class="badge bg-inverse-danger">{{$item->deliveryDetails->driver->title ?? 'Pending'}}</span>
                                       </span>
                                    @endif
                                @else
                                    <span class="badge bg-inverse-warning">Self Delivery</span>
                                @endif

                           </td>
                           <td>

                           @if(($item->status == "canceld" || $item->status == "completed" || $item->status == "reject")) 
                              <span class="badge bg-inverse hover" status="{{$item->status}}">
                                 {{$item->status}}
                              </span>
                           @else

                              <select class="custom-select mr-sm-2" order_id={{$item->id}} status="{{$item->status}}" name="order_status" id="order_status" required>
                                 <option value="pending" @if($item->status == 'pending') selected="selected" @endif >Pending</option>
                                 <option value="reject" @if($item->status == 'reject') selected="selected" @endif >Reject</option>
                                 <option value="processing" @if($item->status == 'processing') selected="selected" @endif >Processing</option>
                                 <option value="delivered" @if($item->status == 'delivered') selected="selected" @endif >Delivered</option>
                                 <option value="completed" @if($item->status == 'completed') selected="selected" @endif >Complete</option>
                                 <option value="canceld" @if($item->status == 'canceld') selected="selected" @endif >Cancel</option>
                              </select>

                           @endif

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

@endsection
