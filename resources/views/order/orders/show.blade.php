@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">{{ $order->title }} <b>[{{ ucfirst($order->status) }}]</b></div>
            <div class="card-body">
               <a href="{{ url('/orders') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/orders/' . $order->id . '/edit') }}" title="Edit Order"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
               <form method="POST" action="{{ url('orders' . '/' . $order->id) }}" accept-charset="UTF-8" style="display:inline">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  {{-- <button type="submit" class="btn btn-danger btn-sm" title="Delete Order" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button> --}}
               </form>
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        <tr>
                           <th> Title </th>
                           <td> {{ $order->title }} </td>
                        </tr>

                        <tr>
                           <th> Branch </th>
                           <td> {{ $order->branchDetails->title }} </td>
                        </tr>

                        <tr>
                           <th> Customer </th>
                           <td> {{ $order->customer->name }} </td>
                        </tr>

                        <tr>
                           <th> Customer </th>
                           <td> {{ $order->customer->name }} </td>
                        </tr>

                        <tr>
                           <th> Delivery Option </th>
                           <td> 
                                @if(!$order->has_delivery) By Customer @endif
                                @if($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'own') By Restaurant @endif
                                @if($order->has_delivery == 1 and $order->deliveryDetails->delivery_type == 'company') By Company @endif
                           </td>
                        </tr>

                        <tr>
                           <th> Total </th>
                           <td> {{ $order->total }} </td>
                        </tr>

                        <tr>
                           <th> Note </th>
                           <td> {{ $order->note }} </td>
                        </tr>

                        <tr>
                           <th> Satus </th>
                           <td> {{ ucfirst($order->status) }} </td>
                        </tr>

                        <tr>
                           <th> Reciever Phone </th>
                           <td> {{ $order->reciever_phone }} </td>
                        </tr>

                        <tr>
                           <th> Items </th>
                           <td> <textarea class="form-control" rows="5" name="contents" type="textarea" id="contents" required>{{ $order->contents ?? ''}}</textarea> </td>
                        </tr>        
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection