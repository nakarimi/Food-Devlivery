@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Item</div>
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
                           <th>Branch Id</th>
                           <th>Price</th>
                           <th>Status</th>
                           
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($item as $item)
                        <tr>
                           <td>{{ $loop->iteration}}</td>
                           <td>
                            <h2 class="table-avatar">
                                    <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' . $item->itemDetails->thumbnail) }}"></a>
                                </h2>
                            </td>
                           <td>{{ $item->itemDetails->title }}</td>
                           <td>{{ $item->branchDetails->title ?? "####" }}</td>
                           <td>{{ $item->itemDetails->price }}</td>
                           <td>
                              @if($item->status == 1)
                                 <span class="badge bg-inverse-success">Active</span>
                              @else
                                 <span class="badge bg-inverse-danger">Inactive</span>
                              @endif
                           </td>
                           <td>
                              <a href="{{ url('/item/' . $item->id) }}" title="View Item"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                              <a href="{{ url('/item/' . $item->id . '/edit') }}" title="Edit Item"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                              <form method="POST" action="{{ url('/item' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                 {{ method_field('DELETE') }}
                                 {{ csrf_field() }}
                                 <button type="submit" class="btn btn-danger btn-xs" title="Delete Item" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                              </form>
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  {{-- There is an issue with pagination of this list, so commented out for now. --}}
                  {{-- 
                  <div class="pagination-wrapper"> {!! $item->appends(['search' => Request::get('search')])->render() !!} </div>
                  --}}
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection