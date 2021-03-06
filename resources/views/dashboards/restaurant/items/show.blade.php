@extends('dashboards.restaurant.layouts.master')
@section('title')
    غذا
@stop

@section('styles')
@stop

@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">test</div>
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
                           <th> عنوان </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->title ?? ''}} </td>
                        </tr>
                        <tr>
                           <th> رستورانت </th>
                           <td> {{ $item->branch->branchDetails->title }} </td>
                        </tr>
                        <tr>
                           <th> قیمت </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->price }} </td>
                        </tr>
                        <tr>
                           <th> قیمت پکیج </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->package_price }} </td>
                        </tr>
                        <tr>
                           <th> واحد </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->unit }} </td>
                        </tr>
                        <tr>
                           <th> توضیحات </th>
                           <td> {{ get_item_details($item, Session::get('itemType'))->description }} </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
               <br>
               <div class="table-responsive">
                  <h1>گذشته غذا</h1>
                  <table class="table table-striped">
                     <thead>
                        <th>ای دی</th>
                        <th>عنوان</th>
                        <th>قیمت</th>
                        <th>واحد</th>
                        <th>توضیحات</th>
                        <th>حالت</th>
                     </thead>
                     <tbody>
                        @foreach($item->itemFullDetails as $detail)
                        <tr>
                           <td>{{$detail->id}}</td>
                           <td>{{$detail->title}}</td>
                           <td>{{$detail->price}}</td>
                           <td>{{$detail->unit}}</td>
                           <td>{{$detail->notes}}</td>
                           <td>
                              @if ($detail->details_status == "pending" and Auth::user()->role->name == "restaurant")
                             <button class="btn btn-sm btn-warning" disabled="disabled" >معطل</button>
                              @elseif ($detail->details_status == "pending")
                               <form method="POST" action="{{ url('/approveItem') }}" accept-charset="UTF-8" style="display:inline">
                                 {{ csrf_field() }}
                                 <input type="hidden" value="{{$detail->id}}" name="item_detail_id">
                                 <input type="hidden" value="{{$item->id }}" name="item_id">
                                 <button class="btn btn-sm btn-info" onclick="return confirm(&quot;Confirm approve?&quot;)">Approve</button>
                              </form>
                              <form method="POST" action="{{ url('/rejectItem') }}" accept-charset="UTF-8" style="display:inline">
                                 {{ csrf_field() }}
                                 <input type="hidden" value="{{$detail->id}}" name="item_detail_id">
                                 <button class="btn btn-sm btn-danger" onclick="return confirm(&quot;Confirm Reject?&quot;)">Reject</button>
                              </form>
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

@section('scripts')

@stop
