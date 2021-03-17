@extends('layouts.master')
@section('title')
   {{ get_item_details($item, Session::get('itemType'))->title}}
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">{{ get_item_details($item, Session::get('itemType'))->title}}</div>
            <div class="profile-img-wrap" style="right: 0px; top: 10px; width: 190px;">
               <div class="profile-img">
                  <a href="#"><img alt="" src="{{ url('storage/profile_images/'.get_item_details($item, Session::get('itemType'))->thumbnail) }}"></a>
               </div>
            </div>
            <div class="card-body">
               <a href="{{ url('/item') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
{{--               <form method="POST" action="{{ url('item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                  {{ method_field('DELETE') }}--}}
{{--                  {{ csrf_field() }}--}}
{{--                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>--}}
{{--               </form>--}}
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $item->id }}</td>
                        </tr>
                         <tr>
                           <th> Title </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->title ?? ''}} </td>
                        </tr>
                        <tr>
                           <th> Branch </th>
                           <td> {{ $item->branch->branchDetails->title }} </td>
                        </tr>
                        <tr>
                           <th> Price </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->price }} </td>
                        </tr>
                        <tr>
                           <th> Package Price </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->package_price }} </td>
                        </tr>
                        <tr>
                           <th> Unit </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->unit }} </td>
                        </tr>
                        <tr>
                           <th> Description </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->description }} </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <br>
               <div class="table-responsive">
                  <h1>Item History</h1>
                  <table class="table table-striped">
                     <thead>
                        <th>ID</th>
                        <th>title</th>
                        <th>Price</th>
                        <th>Unit</th>
                        <th>Status</th>
                     </thead>
                     <tbody>
                        @foreach($item->itemFullDetails as $detail)
                        <tr>
                           <td>{{$detail->id}}</td>
                           <td>{{$detail->title}}</td>
                           <td>{{$detail->price}}</td>
                           <td>{{$detail->unit}}</td>
                           <td>
                              @if ($detail->details_status == "pending" )
                              <form method="POST" action="{{ url('/approveItem') }}" accept-charset="UTF-8" style="display:inline">
                                 {{ csrf_field() }}
                                 <input type="hidden" value="{{$detail->id}}" name="item_detail_id">
                                 <input type="hidden" value="{{$item->id }}" name="item_id">
                                 <button class="btn btn-sm btn-info" onclick="return confirm(&quot;Confirm approve?&quot;)">Approve</button>
                              </form>
                                   <a href="#" class="btn btn-sm btn-danger" title="Reject" data-toggle="modal" data-target="#open_reject_form">Reject</a>
                                   <!-- Add reject reason Modal -->
                                   <div id="open_reject_form" class="modal custom-modal fade" role="dialog">
                                       <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                           <div class="modal-content">
                                               <div class="modal-header">
                                                   <h5 class="modal-title">Any Reject reason?</h5>
                                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                       <span aria-hidden="true">&times;</span>
                                                   </button>
                                               </div>
                                               <div class="modal-body">
                                                   <form action="{{ url('/rejectItem') }}" method="post" enctype="multipart/form-data">
                                                       {!! csrf_field() !!}
                                                       <input type="hidden" value="{{get_item_details($item, 'pending')->id}}" name="item_detail_id">
                                                       <div class="form-group">
                                                           <label>Note:</label>
                                                           <textarea class="form-control" name="note" rows="4"></textarea>
                                                       </div>
                                                       <div class="submit-section">
                                                           <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                                                       </div>
                                                   </form>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <!-- /Add reject reason Modal -->
                              @else
                              {{$detail->details_status }}
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
