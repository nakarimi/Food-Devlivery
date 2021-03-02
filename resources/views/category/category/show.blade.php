@extends('layouts.master')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">{{ $category->title }}</div>
            <div class="profile-img-wrap" style="right: 0px; top: 10px; width: 190px;">
               <div class="profile-img">
                  <a href="#"><img alt="" src="{{ url('storage/profile_images/'.$category->thumbnail) }}"></a>
               </div>
            </div>
            <div class="card-body">
               <a href="{{ url('/category') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
               <a href="{{ url('/category/' . $category->id . '/edit') }}" title="Edit Category"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
               <form method="POST" action="{{ url('category' . '/' . $category->id) }}" accept-charset="UTF-8" style="display:inline">
                  {{ method_field('DELETE') }}
                  {{ csrf_field() }}
                  <button type="submit" class="btn btn-danger btn-sm" title="Delete Category" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
               </form>
               <br/>
               <br/>
               <div class="table-responsive">
                  <table class="table table">
                     <tbody>
                        <tr>
                           <th>ID</th>
                           <td>{{ $category->id }}</td>
                        </tr>
                        <tr>
                           <th> Status </th>
                           <td>
                            @if($category->status == 1)
                                <span class="badge bg-inverse-success">Active</span>
                            @else
                                <span class="badge bg-inverse-danger">Inactive</span>
                            @endif
                           </td>
                        </tr>
                        <tr>
                           <th> Description </th>
                           <td> {{ $category->description }} </td>
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