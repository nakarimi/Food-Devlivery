@extends('layouts.master')
@section('title')
   Items
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Items</div>
            <div class="card-body">
               <a href="{{ url('/item/create') }}" class="btn btn-success btn-sm" title="Add New Item">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/item') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
               <div class="table-responsive itemList">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Thumbnail</th>
                           <th>Title</th>
                           <th>Branch</th>
                           <th>Price</th>
                           <th>Status</th>

                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($item as $singleItem)

                        <?php $itemDetails = get_item_details($singleItem, Session::get('itemType')); ?>
                        
                        <tr>
                           <td>{{ $loop->iteration}}</td>
                           <td>
                            <h2 class="table-avatar">
                                    <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' . $itemDetails->thumbnail) }}"></a>
                                </h2>
                            </td>
                           <td>{{ $itemDetails->title }}</td>
                           <td>{{ @$singleItem->branch->branchDetails->title}}</td>
                           <td>{{ $itemDetails->price }}</td>
                           <td>
                               @if($itemDetails->details_status == "pending")
                                   <span class="badge bg-inverse-warning">Pending</span>
                               @elseif ($itemDetails->detials_status == "rejected")
                                   <span class="badge bg-inverse-danger">Rejected</span>
                               @else
                                   <span class="badge bg-inverse-success">Active</span>
                               @endif
                           </td>
                           <td>
                               @if (\Request::is('pendingItems'))
                                 @include('item.item.pendingActions')
                               @endif
                              <a href="{{ url('/item/' . $singleItem->id) }}" title="View Item"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                              <a href="{{ url('/item/' . $singleItem->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                              <form method="POST" action="{{ url('/item' . '/' . $singleItem->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                 {{ method_field('DELETE') }}--}}
{{--                                 {{ csrf_field() }}--}}
{{--                                 <button type="submit" class="btn btn-danger btn-xs" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
{{--                              </form>--}}
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                   <div class="pagination-wrapper"> {!! $item->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
