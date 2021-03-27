@extends('layouts.master')
@section('title')
   All Branches
@stop
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">Branch</div>
            <div class="card-body">
               <a href="{{ url('/branch/create') }}" class="btn btn-success btn-sm" title="Add New Branch">
               <i class="fa fa-plus" aria-hidden="true"></i> Add New
               </a>
               <form method="GET" action="{{ url('/branch') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0 float-right" role="search">
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
                           <th>Business Type</th>
                           <th>Commission</th>
                            @if (\Request::is('pendingBranches'))
                           <th>Thumbnail</th>
                            @endif
                           <th>Actions</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($branch as $item)
                        <tr>
                           <td>{{ $loop->iteration }}</td>
                           <td>{{ get_branch_details($item, Session::get('branchType'))->title }}</td>
                           <td>{{ $item->business_type }}</td>
                           <td>{{ $item->mainCommission->title }} <br>{{ @$item->deliveryCommission->title }}</td>
                            @if (\Request::is('pendingBranches'))
                            <td>
                                <h2 class="table-avatar">
                                    <a href="#" class="avatar" style="width: 100px; height: 100px; background-color: transparent;"><img alt="" src="{{ url('storage/profile_images/' .get_branch_details($item, Session::get('branchType'))->logo) }}"></a>
                                </h2>
                            </td>
                            @endif
                           <td>
                               @if (\Request::is('pendingBranches'))
                                   <form method="POST" action="{{ url('/approveBranch') }}" accept-charset="UTF-8" style="display:inline">
                                       {{ csrf_field() }}
                                       <input type="hidden" value="{{get_branch_details($item, Session::get('branchType'))->id}}" name="branch_detail_id">
                                       <input type="hidden" value="{{$item->id}}" name="branch_id">
                                       <button class="btn btn-sm btn-success" title="Approve" onclick="return confirm(&quot;Confirm approve?&quot;)"><i class="la la-check"></i></button>
                                   </form>
                                   <form method="POST" action="{{ url('/rejectBranch') }}" accept-charset="UTF-8" style="display:inline">
                                       {{ csrf_field() }}
                                       <input type="hidden" value="{{get_branch_details($item, Session::get('branchType'))->id}}" name="branch_detail_id">
                                       <input type="hidden" value="{{$item->id}}" name="branch_id">
                                       <button class="btn btn-sm btn-danger" title="Reject" onclick="return confirm(&quot;Confirm Reject?&quot;)"><i class="la la-times"></i></button>
                                   </form>
                               @endif

                              <a href="{{ url('/branch/' . $item->id) }}" title="View Branch"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                              <a href="{{ url('/branch/' . $item->id . '/edit') }}" title="Edit Branch"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
{{--                              <form method="POST" action="{{ url('/branch' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">--}}
{{--                                 {{ method_field('DELETE') }}--}}
{{--                                 {{ csrf_field() }}--}}
{{--                                 <button type="submit" class="btn btn-danger btn-xs" title="Delete Branch" onclick="return confirm(&quot;Confirm delete?&quot;)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>--}}
{{--                              </form>--}}
{{--                               <button type="submit" class="btn btn-danger btn-xs" title="Deactive Branch" onclick="return confirm(&quot;Confirm Deactivate?&quot;)"><i class="fa fa-ban" aria-hidden="true"></i></button>--}}
                           </td>
                        </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <div class="pagination-wrapper"> {!! $branch->appends(['search' => Request::get('search')])->render() !!} </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
