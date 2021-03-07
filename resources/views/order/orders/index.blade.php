@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Orders</div>
            <div class="card-body">
               <a href="{{ url('/orders/create') }}" class="btn btn-success btn-sm" title="Add New Order">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/orders') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
                  <div class="input-group">
                     <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                     <span class="input-group-append">
                     <button class="btn btn-secondary" type="submit">
                     <i class="fa fa-search"></i>
                     </button>
                     </span>
                  </div>
               </form>
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
                           <td>{{ $item->customer->name }}</td>
                           <td>
                                @if($item->has_delivery == 1)
                                    @if($item->deliveryDetails->delivery_type == 'own')
                                        <span class="badge bg-inverse-success">Own Delivery</span>
                                    @else
                                        <span class="badge bg-inverse-primary">Company Delivery</span>
                                    @endif
                                @else
                                    <span class="badge bg-inverse-warning">Self Delivery2</span>
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

@endsection
