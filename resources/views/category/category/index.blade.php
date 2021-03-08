@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Category</div>
            <div class="card-body">
               <a href="{{ url('/category/create') }}" class="btn btn-success btn-sm" title="Add New Category">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/category') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
               <div class="table-responsive itemCategories">
                  <table class="table">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Thumbnail</th>
                           <th>Title</th>
                           <th>Description</th>
                           <th>Status</th>
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($category as $item)
                        <tr>
                           <td>{{ $loop->iteration }}</td>
                           <td>
                                <h2 class="table-avatar">
                                    <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/'.$item->thumbnail) }}"></a>
                                </h2>
                            </td>
                           <td>{{ $item->title }}</td>
                           <td>{{ $item->description }}</td>
                           <td>
                            @if($item->status == 1)
                                <span class="badge bg-inverse-success">Active</span>
                            @else
                                <span class="badge bg-inverse-danger">Inactive</span>
                            @endif
                           </td>
                           <td>
                              <a href="{{ url('/category/' . $item->id) }}" title="View Category"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                              <a href="{{ url('/category/' . $item->id . '/edit') }}" title="Edit Category"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                              <form method="POST" action="{{ url('/category' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                 {{ method_field('DELETE') }}--}}
{{--                                 {{ csrf_field() }}--}}
{{--                                 <button type="submit" class="btn btn-danger btn-xs" title="Delete Category" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
{{--                              </form>--}}
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $category->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
